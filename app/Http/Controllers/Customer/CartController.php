<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the cart.
     */
    public function index()
    {
        if (Auth::check()) {
            // Get cart items from database for logged in user
            $cartItems = Cart::where('user_id', Auth::id())
                ->with('product')
                ->get();
        } else {
            // Get cart items from session for guest
            $cartItems = collect(Session::get('cart', []));
            
            if ($cartItems->isNotEmpty()) {
                $productIds = $cartItems->pluck('product_id')->toArray();
                $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
                
                $cartItems = $cartItems->map(function ($item) use ($products) {
                    // $item di sini array, jadi akses pakai ['key']
                    $item['product'] = $products[$item['product_id']] ?? null;
                    // Setelah semua properti di-set, baru cast ke object
                    return (object) $item;
                })->filter(function ($item) {
                    // $item sudah object di sini
                    return $item->product !== null;
                });
            }
        }
        
        // Calculate totals
        $subtotal = 0;
        
        foreach ($cartItems as $item) {
            $price = $item->product->discount_price ?? $item->product->price;
            $subtotal += $price * $item->quantity;
        }
        
        $total = $subtotal;
        
        return view('customer.cart', compact('cartItems', 'subtotal', 'total'));
    }
    
    /**
     * Add a product to the cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required|integer|min:36|max:44',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $product = Product::findOrFail($request->product_id);
        
        // Check if product is active and in stock
        if (!$product->is_active || $product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available or insufficient stock',
            ]);
        }
        
        if (Auth::check()) {
            // Add to database cart for logged in user
            $cartItem = Cart::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'size' => $request->size,
                ],
                [
                    'quantity' => $request->quantity,
                ]
            );
            
            $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
        } else {
            // Add to session cart for guest
            $cart = Session::get('cart', []);
            
            $found = false;
            foreach ($cart as &$item) {
                if ($item['product_id'] == $product->id && $item['size'] == $request->size) {
                    $item['quantity'] = $request->quantity;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $cart[] = [
                    'product_id' => $product->id,
                    'size' => $request->size,
                    'quantity' => $request->quantity,
                ];
            }
            
            Session::put('cart', $cart);
            
            $cartCount = collect($cart)->sum('quantity');
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart',
                'cart_count' => $cartCount,
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Product added to cart');
    }
    
    /**
     * Update cart item quantity.
     */
    public function update(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'size' => 'nullable',
            ]);
            
            $product = Product::findOrFail($request->product_id);
            
            // Check if product is active and in stock
            if (!$product->is_active || $product->stock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product is not available or insufficient stock',
                ]);
            }
            
            if (Auth::check()) {
                // Update database cart for logged in user
                $query = Cart::where('user_id', Auth::id())
                    ->where('product_id', $product->id);
                
                if ($request->filled('size')) {
                    $query->where('size', $request->size);
                }
                
                $updated = $query->update(['quantity' => $request->quantity]);
                
                if (!$updated) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cart item not found',
                    ]);
                }
                    
                $cartItem = $query->with('product')->first();
                    
                $subtotal = $cartItem ? ($cartItem->product->discount_price ?? $cartItem->product->price) * $cartItem->quantity : 0;
                $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
            } else {
                // Update session cart for guest
                $cart = Session::get('cart', []);
                $updated = false;
                
                foreach ($cart as &$item) {
                    $sizeMatch = (!$request->filled('size') && !isset($item['size'])) || 
                               ($request->filled('size') && isset($item['size']) && $item['size'] == $request->size);
                               
                    if ($item['product_id'] == $product->id && $sizeMatch) {
                        $item['quantity'] = $request->quantity;
                        $updated = true;
                        break;
                    }
                }
                
                if (!$updated) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cart item not found',
                    ]);
                }
                
                Session::put('cart', $cart);
                
                $price = $product->discount_price ?? $product->price;
                $subtotal = $price * $request->quantity;
                $cartCount = collect($cart)->sum('quantity');
            }
            
            return response()->json([
                'success' => true,
                'subtotal' => $subtotal,
                'cart_count' => $cartCount,
                'formatted_subtotal' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Cart update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating cart: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Remove an item from the cart.
     */
    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        
        if (Auth::check()) {
            // Remove from database cart for logged in user
            Cart::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->delete();
                
            $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
        } else {
            // Remove from session cart for guest
            $cart = Session::get('cart', []);
            
            foreach ($cart as $key => $item) {
                if ($item['product_id'] == $request->product_id) {
                    unset($cart[$key]);
                    break;
                }
            }
            
            Session::put('cart', array_values($cart));
            
            $cartCount = collect($cart)->sum('quantity');
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'cart_count' => $cartCount,
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Item removed from cart');
    }
    
    /**
     * Clear the cart.
     */
    public function clear()
    {
        if (Auth::check()) {
            // Clear database cart for logged in user
            Cart::where('user_id', Auth::id())->delete();
        } else {
            // Clear session cart for guest
            Session::forget('cart');
        }
        
        return redirect()->route('cart.index')->with('success', 'Cart cleared');
    }
}
