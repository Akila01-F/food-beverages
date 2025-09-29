<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'orderItems.product']);

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->search . '%')
                                ->orWhere('email', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $orders = $query->paginate(15)->appends($request->all());

        // Statistics
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'confirmed_orders' => Order::where('status', 'confirmed')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->count(),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'today_revenue' => Order::whereDate('created_at', today())->where('status', 'completed')->sum('total_amount'),
            'total_revenue' => Order::where('status', 'completed')->sum('total_amount'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Display the specified order
     */
    public function show(Order $order): View
    {
        $order->load(['user', 'orderItems.product']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,out_for_delivery,delivered,cancelled'
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        return back()->with('success', "Order status updated from '{$oldStatus}' to '{$request->status}'");
    }

    /**
     * Handle bulk actions on orders
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:confirm,cancel,mark_delivered',
            'selected_orders' => 'required|array|min:1',
            'selected_orders.*' => 'exists:orders,id'
        ]);

        $orders = Order::whereIn('id', $request->selected_orders)->get();

        switch ($request->action) {
            case 'confirm':
                Order::whereIn('id', $orders->pluck('id'))->update(['status' => 'confirmed']);
                return back()->with('success', count($orders) . " orders confirmed!");

            case 'cancel':
                Order::whereIn('id', $orders->pluck('id'))->update(['status' => 'cancelled']);
                return back()->with('success', count($orders) . " orders cancelled!");

            case 'mark_delivered':
                Order::whereIn('id', $orders->pluck('id'))->update(['status' => 'delivered']);
                return back()->with('success', count($orders) . " orders marked as delivered!");

            default:
                return back()->with('error', 'Invalid action selected.');
        }
    }

    /**
     * Delete the specified order
     */
    public function destroy(Order $order): RedirectResponse
    {
        $orderId = $order->id;
        
        // Delete order items first
        $order->orderItems()->delete();
        
        // Delete the order
        $order->delete();

        return redirect()
            ->route('admin.orders.index')
            ->with('success', "Order #{$orderId} deleted successfully!");
    }

    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->search . '%')
                                ->orWhere('email', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->get();

        $filename = 'orders_' . date('Y_m_d_H_i_s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'Order ID',
                'Customer Name',
                'Customer Email',
                'Status',
                'Total Amount',
                'Items Count',
                'Order Date',
                'Updated Date'
            ]);

            // CSV Data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->user->name ?? 'Guest',
                    $order->user->email ?? 'N/A',
                    ucfirst($order->status),
                    number_format($order->total_amount, 2),
                    $order->orderItems->count(),
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->updated_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get order statistics for dashboard
     */
    public function getStatistics()
    {
        return [
            'daily_orders' => Order::whereDate('created_at', today())->count(),
            'weekly_orders' => Order::whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count(),
            'monthly_orders' => Order::whereMonth('created_at', date('m'))
                                   ->whereYear('created_at', date('Y'))
                                   ->count(),
            'daily_revenue' => Order::whereDate('created_at', today())
                                   ->where('status', 'completed')
                                   ->sum('total_amount'),
            'weekly_revenue' => Order::whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->where('status', 'completed')->sum('total_amount'),
            'monthly_revenue' => Order::whereMonth('created_at', date('m'))
                                     ->whereYear('created_at', date('Y'))
                                     ->where('status', 'completed')
                                     ->sum('total_amount'),
        ];
    }
}