<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->with('orderItems.product')->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('orderItems.product');
        return view('orders.show', compact('order'));
    }

    public function checkout()
    {
        $cartItems = session('cart', []);
        
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // Calculate totals
        $subtotal = 0;
        $cartProducts = [];
        
        foreach ($cartItems as $id => $item) {
            $product = Product::find($id);
            if ($product) {
                $price = $product->discounted_price ?? $product->price;
                $subtotal += $price * $item['quantity'];
                $cartProducts[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $price
                ];
            }
        }
        
        $tax = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $tax;

        return view('orders.checkout', compact('cartProducts', 'subtotal', 'tax', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:cash_on_delivery,card'
        ]);

        $cartItems = session('cart', []);
        
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        DB::transaction(function () use ($request, $cartItems) {
            // Calculate totals
            $subtotal = 0;
            foreach ($cartItems as $id => $item) {
                $product = Product::find($id);
                if ($product) {
                    $price = $product->discounted_price ?? $product->price;
                    $subtotal += $price * $item['quantity'];
                }
            }
            
            $tax = $subtotal * 0.1;
            $total = $subtotal + $tax;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'total_amount' => $total,
                'delivery_address' => [
                    'address' => $request->shipping_address,
                    'phone' => $request->phone
                ],
                'notes' => $request->notes,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending'
            ]);

            // Create order items
            foreach ($cartItems as $id => $item) {
                $product = Product::find($id);
                if ($product) {
                    $price = $product->discounted_price ?? $product->price;
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $price,
                        'total_price' => $price * $item['quantity']
                    ]);

                    // Update stock
                    $product->decrement('stock_quantity', $item['quantity']);
                }
            }
        });

        // Clear cart
        session()->forget('cart');

        return redirect()->route('orders.success')->with('success', 'Order placed successfully!');
    }

    public function success()
    {
        return view('orders.success');
    }
}
