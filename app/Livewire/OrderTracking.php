<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class OrderTracking extends Component
{
    use WithPagination;

    public $selectedOrderId = null;
    public $searchOrderNumber = '';
    public $statusFilter = '';
    public $dateFilter = '';
    public $showOrderDetails = false;

    protected $queryString = [
        'searchOrderNumber' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFilter' => ['except' => ''],
    ];

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    }

    public function updatingSearchOrderNumber()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function viewOrderDetails($orderId)
    {
        $order = Order::where('id', $orderId)
                     ->where('user_id', Auth::id())
                     ->first();

        if ($order) {
            $this->selectedOrderId = $orderId;
            $this->showOrderDetails = true;
        }
    }

    public function closeOrderDetails()
    {
        $this->selectedOrderId = null;
        $this->showOrderDetails = false;
    }

    public function trackOrder($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
                     ->where('user_id', Auth::id())
                     ->first();

        if ($order) {
            $this->selectedOrderId = $order->id;
            $this->showOrderDetails = true;
            $this->searchOrderNumber = $orderNumber;
        } else {
            $this->dispatch('order-not-found');
        }
    }

    public function cancelOrder($orderId)
    {
        $order = Order::where('id', $orderId)
                     ->where('user_id', Auth::id())
                     ->where('status', 'pending')
                     ->first();

        if ($order) {
            $order->update(['status' => 'cancelled']);
            $this->dispatch('order-cancelled');
        }
    }

    public function reorder($orderId)
    {
        $order = Order::with('orderItems.product')
                     ->where('id', $orderId)
                     ->where('user_id', Auth::id())
                     ->first();

        if ($order) {
            // Clear current cart and add order items
            session()->forget('cart');
            $cart = [];

            foreach ($order->orderItems as $item) {
                if ($item->product && $item->product->is_active) {
                    $cart[$item->product_id] = [
                        'product' => $item->product,
                        'quantity' => $item->quantity,
                        'price' => $item->product->discounted_price ?? $item->product->price,
                    ];
                }
            }

            session(['cart' => $cart]);
            $this->dispatch('cart-updated');
            return redirect()->route('cart.index');
        }
    }

    public function downloadInvoice($orderId)
    {
        $order = Order::where('id', $orderId)
                     ->where('user_id', Auth::id())
                     ->first();

        if ($order && in_array($order->status, ['completed', 'delivered'])) {
            return redirect()->route('orders.invoice', $order);
        }
    }

    public function getStatusBadgeColor($status)
    {
        return match($status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-blue-100 text-blue-800',
            'preparing' => 'bg-orange-100 text-orange-800',
            'ready' => 'bg-purple-100 text-purple-800',
            'delivered' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getProgressPercentage($status)
    {
        return match($status) {
            'pending' => 20,
            'confirmed' => 40,
            'preparing' => 60,
            'ready' => 80,
            'delivered' => 100,
            'cancelled' => 0,
            default => 0,
        };
    }

    public function render()
    {
        $query = Order::where('user_id', Auth::id())
            ->with(['orderItems.product']);

        // Search by order number
        if ($this->searchOrderNumber) {
            $query->where('order_number', 'like', '%' . $this->searchOrderNumber . '%');
        }

        // Filter by status
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Filter by date
        if ($this->dateFilter) {
            switch ($this->dateFilter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->subWeek(), now()]);
                    break;
                case 'month':
                    $query->whereBetween('created_at', [now()->subMonth(), now()]);
                    break;
                case '3months':
                    $query->whereBetween('created_at', [now()->subMonths(3), now()]);
                    break;
            }
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get selected order details if viewing
        $selectedOrder = null;
        if ($this->selectedOrderId) {
            $selectedOrder = Order::with(['orderItems.product'])
                                 ->where('id', $this->selectedOrderId)
                                 ->where('user_id', Auth::id())
                                 ->first();
        }

        // Get order status counts
        $statusCounts = Order::where('user_id', Auth::id())
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('livewire.order-tracking', [
            'orders' => $orders,
            'selectedOrder' => $selectedOrder,
            'statusCounts' => $statusCounts,
        ]);
    }
}