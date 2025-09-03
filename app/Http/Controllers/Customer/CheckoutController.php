<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingAddress;
use App\Services\MidtransService;
use App\Services\ShipperService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Mail\CheckoutSuccessMail;

class CheckoutController extends Controller
{
    protected $midtransService;
    protected $shipperService;

    public function __construct(MidtransService $midtransService, ShipperService $shipperService)
    {
        $this->midtransService = $midtransService;
        $this->shipperService = $shipperService;
    }

    /**
     * Display the checkout page.
     */
    public function index()
    {
        // Check if cart is empty
        if (Auth::check()) {
            $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
            
            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }
        } else {
            $sessionCart = Session::get('cart', []);
            
            if (empty($sessionCart)) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }
            
            $productIds = array_column($sessionCart, 'product_id');
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
            
            $cartItems = collect();
            foreach ($sessionCart as $item) {
                if (isset($products[$item['product_id']])) {
                    $cartItem = (object) [
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'product' => $products[$item['product_id']],
                    ];
                    $cartItems->push($cartItem);
                }
            }
            
            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }
        }
        
        // Calculate totals
        $subtotal = 0;
        $discount = 0;
        
        foreach ($cartItems as $item) {
            $price = $item->product->discount_price ?? $item->product->price;
            $subtotal += $price * $item->quantity;
        }
        
        // Get applied coupon
        $coupon = null;
        $couponCode = Session::get('coupon_code');
        
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid() && $subtotal >= $coupon->min_amount) {
                $discount = $coupon->calculateDiscount($subtotal);
            } else {
                // Remove invalid coupon
                Session::forget('coupon_code');
            }
        }
        
        $total = $subtotal - $discount;
        
        // Get shipping addresses for logged in user
        $shippingAddresses = [];
        if (Auth::check()) {
            $shippingAddresses = ShippingAddress::where('user_id', Auth::id())->get();
        }
        
        return view('customer.checkout', compact(
            'cartItems',
            'subtotal',
            'discount',
            'total',
            'coupon',
            'shippingAddresses'
        ));
    }
    
    /**
     * Process the checkout.
     */
    public function process(Request $request)
    {
        Log::info('Checkout process started');
        Log::info('Request data:', $request->all());
        
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:500',
                'city' => 'required|string|max:100',
                'province' => 'required|string|max:100',
                'postal_code' => 'required|string|max:10',
                'shipping_expedition' => 'required|string',
                'shipping_cost' => 'nullable|numeric|min:0',
                'cod_fee' => 'nullable|numeric|min:0',
                'payment_method' => 'nullable|string',
                'notes' => 'nullable|string|max:1000',
                'save_address' => 'nullable|boolean'
            ]);
            
            Log::info('Validation passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid',
                    'errors' => $e->errors()
                ], 422);
            }
            
            throw $e;
        }
        
        // Get cart items
        Log::info('Getting cart items for user: ' . (Auth::check() ? Auth::id() : 'guest'));
        
        if (Auth::check()) {
            $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
            Log::info('Cart items count: ' . $cartItems->count());
            
            if ($cartItems->isEmpty()) {
                Log::info('Cart is empty, redirecting to cart');
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }
        } else {
            $sessionCart = Session::get('cart', []);
            Log::info('Session cart count: ' . count($sessionCart));
            
            if (empty($sessionCart)) {
                Log::info('Session cart is empty, redirecting to cart');
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }
            
            $productIds = array_column($sessionCart, 'product_id');
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
            
            $cartItems = collect();
            foreach ($sessionCart as $item) {
                if (isset($products[$item['product_id']])) {
                    $cartItem = (object) [
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'product' => $products[$item['product_id']],
                    ];
                    $cartItems->push($cartItem);
                }
            }
            
            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }
        }
        
        // Calculate totals
        $subtotal = 0;
        $discount = 0;
        
        // Validate shipping expedition exists
        if (!$request->shipping_expedition) {
            Log::error('Missing shipping expedition');
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan pilih ekspedisi pengiriman'
                ], 422);
            }
            return back()->withInput()->with('error', 'Silakan pilih ekspedisi pengiriman');
        }
        
        foreach ($cartItems as $item) {
            $price = $item->product->discount_price ?? $item->product->price;
            $subtotal += $price * $item->quantity;
        }
        
        // Set shipping cost with default
        $shippingCost = (int)($validatedData['shipping_cost'] ?? 15000);
        $codFee = (int)($validatedData['cod_fee'] ?? 0);
        $paymentMethod = $validatedData['payment_method'] ?? 'midtrans';
        
        if (!$shippingCost || $shippingCost < 1000) {
            $shippingCost = 15000;
        }
        
        // Apply coupon if available
        $coupon = null;
        $couponCode = Session::get('coupon_code');
        
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid() && $subtotal >= $coupon->min_amount) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }
        
        $total = $subtotal - $discount + $shippingCost + $codFee;
        
        // Get shipping expedition details
        $expeditionDetails = $this->getExpeditionDetails($validatedData['shipping_expedition']);
        
        // Begin transaction
        DB::beginTransaction();
        
        try {
            // Create shipping address if requested
            if (Auth::check() && $request->has('save_address') && $request->save_address) {
                ShippingAddress::create([
                    'user_id' => Auth::id(),
                    'name' => $validatedData['name'],
                    'phone' => $validatedData['phone'],
                    'address' => $validatedData['address'],
                    'city' => $validatedData['city'],
                    'province' => $validatedData['province'],
                    'postal_code' => $validatedData['postal_code'],
                    'is_default' => !ShippingAddress::where('user_id', Auth::id())->exists(),
                ]);
            }
            
            // Format shipping address for order
            $shippingAddress = json_encode([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'address' => $validatedData['address'],
                'city' => $validatedData['city'],
                'province' => $validatedData['province'],
                'postal_code' => $validatedData['postal_code'],
            ]);
            
            // Generate order number
            $orderNumber = 'ORD-' . strtoupper(Str::random(10));
            
            // Create order
            $order = Order::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'order_number' => $orderNumber,
                'status' => 'pending',
                'total_amount' => $total,
                'shipping_cost' => $shippingCost,
                'discount_amount' => $discount,
                'cod_fee' => $codFee,
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'shipping_address' => $shippingAddress,
                'shipping_expedition' => $validatedData['shipping_expedition'],
                'shipping_expedition_name' => $expeditionDetails['name'],
                'shipping_estimation' => $expeditionDetails['estimation'],
                'notes' => $validatedData['notes'] ?? null,
            ]);
            
            // Create order items
            foreach ($cartItems as $item) {
                $price = $item->product->discount_price ?? $item->product->price;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'size' => $item->size ?? null,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'total' => $price * $item->quantity,
                ]);
                
                // Update product stock
                $product = Product::find($item->product_id);
                $product->stock -= $item->quantity;
                $product->save();
            }
            
            // Update coupon usage if applied
            if ($coupon) {
                $coupon->incrementUsage();
            }
            
            // Send checkout success email
            $this->sendCheckoutSuccessEmail($order);
            
            DB::commit();
            
            // Clear cart and coupon only after successful order creation
            if (Auth::check()) {
                Cart::where('user_id', Auth::id())->delete();
            } else {
                Session::forget('cart');
            }
            
            // Clear coupon
            Session::forget('coupon_code');
            
            // Create Midtrans Snap Token
            try {
                Log::info('Creating Midtrans snap token for order: ' . $order->order_number);
                Log::info('Payment method: ' . $paymentMethod);
                Log::info('Order total: ' . $order->total_amount);
                
                $snapToken = $this->midtransService->createSnapToken($order);
                Log::info('Snap token created successfully: ' . $snapToken);
                
                // Store order in session 
                Session::put('completed_order', $order->id);
                
                // Return JSON response for AJAX
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'snap_token' => $snapToken,
                        'order_id' => $order->id,
                        'message' => 'Order created successfully'
                    ]);
                }
                
                // Fallback: Redirect to payment page
                return redirect()->route('checkout.payment', $order->id)
                    ->with('snap_token', $snapToken);
                    
            } catch (\Exception $e) {
                Log::error('Midtrans error during checkout: ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());
                
                // If Midtrans fails, rollback the order creation
                DB::rollBack();
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment gateway error: ' . $e->getMessage()
                    ], 400);
                }
                
                return back()->withInput()->with('error', 'Payment gateway error: ' . $e->getMessage());
            }
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error during checkout:', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql() ?? 'N/A',
                'bindings' => $e->getBindings() ?? [],
                'user_id' => Auth::id()
            ]);
            DB::rollBack();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Database error occurred. Please try again.'
                ], 500);
            }
            
            return back()->withInput()->with('error', 'Database error occurred. Please try again.');
            
        } catch (\Exception $e) {
            Log::error('General checkout error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'validated_data' => $validatedData ?? [],
                'request_data' => $request->all()
            ]);
            DB::rollBack();
            
            $errorMessage = 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.';
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'debug' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
            
            return back()->withInput()->with('error', $errorMessage);
        }
    }
    
    /**
     * Get expedition details by code.
     */
    private function getExpeditionDetails($expeditionCode)
    {
        $expeditions = [
            'jne_reg' => [
                'name' => 'JNE REG (Regular)',
                'estimation' => '2-3 hari kerja'
            ],
            'jne_yes' => [
                'name' => 'JNE YES (Yakin Esok Sampai)',
                'estimation' => '1-2 hari kerja'
            ],
            'jnt_regular' => [
                'name' => 'J&T Regular',
                'estimation' => '2-4 hari kerja'
            ],
            'jnt_express' => [
                'name' => 'J&T Express',
                'estimation' => '1-2 hari kerja'
            ],
            'sicepat_regular' => [
                'name' => 'SiCepat REG',
                'estimation' => '2-3 hari kerja'
            ],
            'sicepat_halu' => [
                'name' => 'SiCepat HALU (Hari Itu Sampai)',
                'estimation' => '1 hari kerja'
            ],
            'pos_regular' => [
                'name' => 'Pos Reguler',
                'estimation' => '3-5 hari kerja'
            ],
            'pos_express' => [
                'name' => 'Pos Kilat Khusus',
                'estimation' => '1-2 hari kerja'
            ],
        ];
        
        return $expeditions[$expeditionCode] ?? [
            'name' => 'Unknown Expedition',
            'estimation' => 'Unknown'
        ];
    }
    
    /**
     * Display Midtrans payment page
     */
    public function payment(Order $order)
    {
        // Check if user can access this order
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order');
        }

        $snapToken = session('snap_token');
        
        if (!$snapToken) {
            // Try to recreate snap token
            try {
                $snapToken = $this->midtransService->createSnapToken($order);
            } catch (\Exception $e) {
                Log::error('Failed to create snap token for order: ' . $order->order_number . ' - ' . $e->getMessage());
                return redirect()->route('checkout.index')
                    ->with('error', 'Payment gateway error: ' . $e->getMessage());
            }
        }

        return view('customer.checkout-payment', compact('order', 'snapToken'));
    }

    /**
     * Display the checkout success page.
     */
    public function success()
    {
        $orderId = Session::get('completed_order');
        
        if (!$orderId) {
            return redirect()->route('home');
        }
        
        $order = Order::with(['items.product'])->findOrFail($orderId);
        
        // Get Midtrans transaction details for invoice
        $transactionDetails = null;
        if ($order->payment_method === 'midtrans') {
            $transactionDetails = $this->midtransService->getTransactionDetails($order);
        }
        
        // Clear the session data
        Session::forget('completed_order');
        
        return view('customer.checkout-success', compact('order', 'transactionDetails'));
    }

    /**
     * Continue payment for existing order
     */
    public function continuePayment(Order $order)
    {
        // Check if user can access this order
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order');
        }

        // Only allow payment continuation for pending orders
        if ($order->payment_status !== 'pending') {
            return redirect()->route('profile.orders')
                ->with('error', 'Order payment cannot be continued.');
        }

        try {
            $snapToken = $this->midtransService->createSnapToken($order);
            return view('customer.continue-payment', compact('order', 'snapToken'));
        } catch (\Exception $e) {
            Log::error('Failed to create snap token for order: ' . $order->order_number . ' - ' . $e->getMessage());
            return redirect()->route('profile.orders')
                ->with('error', 'Payment gateway error: ' . $e->getMessage());
        }
    }

    /**
     * Calculate shipping cost via AJAX
     */
    public function calculateShipping(Request $request)
    {
        $request->validate([
            'city' => 'required|string'
        ]);

        $city = $request->city;
        $baseCost = \App\Models\ShippingZone::getShippingCost($city);
        
        // Berbagai ekspedisi dengan harga berbeda berdasarkan jarak dari Jakarta
        $expeditions = [
            'jne_reg' => ['name' => 'JNE REG', 'multiplier' => 1.0, 'estimation' => '2-3 hari'],
            'jne_yes' => ['name' => 'JNE YES', 'multiplier' => 1.5, 'estimation' => '1-2 hari'],
            'jnt_reg' => ['name' => 'J&T REG', 'multiplier' => 0.9, 'estimation' => '2-4 hari'],
            'sicepat_reg' => ['name' => 'SiCepat REG', 'multiplier' => 0.8, 'estimation' => '2-3 hari']
        ];
        
        $shippingOptions = [];
        foreach ($expeditions as $code => $expedition) {
            $cost = (int)($baseCost * $expedition['multiplier']);
            $shippingOptions[$code] = [
                'cost' => $cost,
                'estimation' => $expedition['estimation'],
                'logistic' => explode(' ', $expedition['name'])[0],
                'service' => $expedition['name']
            ];
        }

        return response()->json($shippingOptions);
    }

    /**
     * Calculate total weight of cart items
     */
    private function calculateCartWeight()
    {
        $totalWeight = 0;
        
        if (Auth::check()) {
            $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        } else {
            $sessionCart = Session::get('cart', []);
            $productIds = array_column($sessionCart, 'product_id');
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
            
            $cartItems = collect();
            foreach ($sessionCart as $item) {
                if (isset($products[$item['product_id']])) {
                    $cartItem = (object) [
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'product' => $products[$item['product_id']],
                    ];
                    $cartItems->push($cartItem);
                }
            }
        }
        
        foreach ($cartItems as $item) {
            $totalWeight += ($item->product->weight ?? 500) * $item->quantity;
        }
        
        return max(500, $totalWeight); // Minimum 500g
    }

    /**
     * Send checkout success email to customer.
     */
    private function sendCheckoutSuccessEmail(Order $order)
    {
        try {
            $email = $order->user->email ?? json_decode($order->shipping_address)->email;
            
            if ($email) {
                Mail::to($email)->send(new CheckoutSuccessMail($order));
                Log::info("Checkout success email sent for order: {$order->order_number}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to send checkout success email: " . $e->getMessage());
        }
    }

}
