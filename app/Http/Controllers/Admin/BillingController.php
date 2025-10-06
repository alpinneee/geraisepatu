<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MidtransService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class BillingController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function index(Request $request)
    {
        // Filter parameters
        $status = $request->get('status');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $perPage = $request->get('per_page', 15);

        // Build query for transactions
        $transactionsQuery = Order::with('user')
            ->when($status, function ($query, $status) {
                return $query->where('payment_status', $status);
            })
            ->when($dateFrom, function ($query, $dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query, $dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            })
            ->orderBy('created_at', 'desc');

        $transactions = $transactionsQuery->paginate($perPage);

        // Revenue calculations
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $todayRevenue = Order::where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->sum('total_amount');
        $weekRevenue = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('total_amount');
        $monthRevenue = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        // Status counts
        $statusCounts = [
            'paid' => Order::where('payment_status', 'paid')->count(),
            'pending' => Order::where('payment_status', 'pending')->count(),
            'failed' => Order::where('payment_status', 'failed')->count(),
            'expired' => Order::where('payment_status', 'expired')->count(),
        ];

        // Chart data for last 7 days
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $revenue = Order::where('payment_status', 'paid')
                ->whereDate('created_at', $date)
                ->sum('total_amount');
            $chartData[] = [
                'date' => $date->format('M d'),
                'revenue' => $revenue
            ];
        }

        return view('admin.billing.index', compact(
            'transactions',
            'totalRevenue',
            'todayRevenue',
            'weekRevenue',
            'monthRevenue',
            'statusCounts',
            'chartData',
            'status',
            'dateFrom',
            'dateTo'
        ));
    }

    public function syncStatus(Request $request)
    {
        $orderId = $request->get('order_id');
        
        if ($orderId) {
            $order = Order::find($orderId);
            if ($order && $order->midtrans_order_id) {
                $status = $this->midtransService->getTransactionStatus($order->midtrans_order_id);
                $order->update(['payment_status' => $status]);
                return response()->json(['success' => true, 'status' => $status]);
            }
        }
        
        return response()->json(['success' => false]);
    }

    public function downloadInvoice($orderId)
    {
        $order = Order::with(['user', 'orderItems.product'])->findOrFail($orderId);
        
        $pdf = Pdf::loadView('admin.billing.invoice', compact('order'));
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }

    public function webhook(Request $request)
    {
        $notification = $request->all();
        
        $orderId = $notification['order_id'] ?? null;
        $transactionStatus = $notification['transaction_status'] ?? null;
        $fraudStatus = $notification['fraud_status'] ?? null;
        
        if ($orderId) {
            $order = Order::where('midtrans_order_id', $orderId)->first();
            
            if ($order) {
                $paymentStatus = 'pending';
                
                if ($transactionStatus == 'capture') {
                    $paymentStatus = ($fraudStatus == 'challenge') ? 'pending' : 'paid';
                } elseif ($transactionStatus == 'settlement') {
                    $paymentStatus = 'paid';
                } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                    $paymentStatus = 'failed';
                } elseif ($transactionStatus == 'pending') {
                    $paymentStatus = 'pending';
                }
                
                $order->update(['payment_status' => $paymentStatus]);
            }
        }
        
        return response()->json(['status' => 'ok']);
    }
}