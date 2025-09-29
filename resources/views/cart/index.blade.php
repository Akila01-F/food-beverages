<x-main-layout>
    <div class="bg-food-cream py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-food-dark mb-2">🛒 Your Cart</h1>
                <p class="text-food-text-muted">Review your items before checkout</p>
            </div>

            @if(session('success'))
                <div class="bg-food-success text-white p-4 rounded-lg mb-6 shadow-md">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-food-error text-white p-4 rounded-lg mb-6 shadow-md">
                    {{ session('error') }}
                </div>
            @endif

            @if(empty($cartItems))
                <div class="bg-white rounded-lg shadow-md p-12 text-center border border-food-light-dark">
                    <div class="text-6xl mb-4">🛒</div>
                    <h2 class="text-2xl font-semibold text-food-dark mb-4">Your cart is empty</h2>
                    <p class="text-food-text-muted mb-6">Looks like you haven't added any delicious items to your cart yet.</p>
                    <a href="{{ route('products.index') }}" class="bg-food-primary text-white px-8 py-3 rounded-lg hover:bg-food-primary-dark transition shadow-md">
                        🍽️ Browse Menu
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="p-6 border-b">
                                <h2 class="text-xl font-semibold text-food-dark">Cart Items ({{ count($cartItems) }})</h2>
                            </div>
                            
                            <div class="divide-y">
                                @foreach($cartItems as $item)
                                    <div class="p-6 flex items-center space-x-4">
                                        <!-- Product Image Placeholder -->
                                        <div class="w-20 h-20 bg-gradient-to-br from-food-secondary to-food-primary rounded-lg flex items-center justify-center">
                                            <span class="text-2xl">🍽️</span>
                                        </div>
                                        
                                        <!-- Product Details -->
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-food-dark">{{ $item['product']->name }}</h3>
                                            <p class="text-sm text-food-text-muted">{{ $item['product']->category->name }}</p>
                                            <p class="text-food-primary font-semibold">${{ number_format($item['product']->final_price, 2) }}</p>
                                        </div>
                                        
                                        <!-- Quantity Controls -->
                                        <div class="flex items-center space-x-2">
                                            <form action="{{ route('cart.update', $item['product']) }}" method="POST" class="flex items-center space-x-2">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button" onclick="decreaseQuantity({{ $item['product']->id }})" class="w-8 h-8 bg-food-light-dark rounded-full flex items-center justify-center hover:bg-food-text-muted text-food-dark hover:text-white transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                    </svg>
                                                </button>
                                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 text-center border border-food-border rounded-lg focus:ring-2 focus:ring-food-primary focus:border-transparent" id="quantity-{{ $item['product']->id }}">
                                                <button type="button" onclick="increaseQuantity({{ $item['product']->id }})" class="w-8 h-8 bg-food-light-dark rounded-full flex items-center justify-center hover:bg-food-text-muted text-food-dark hover:text-white transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                </button>
                                                <button type="submit" class="bg-food-primary text-white px-3 py-1 rounded-lg text-sm hover:bg-food-primary-dark transition shadow-sm">
                                                    Update
                                                </button>
                                            </form>
                                        </div>
                                        
                                        <!-- Subtotal -->
                                        <div class="text-right">
                                            <div class="font-semibold text-food-dark">${{ number_format($item['subtotal'], 2) }}</div>
                                            <form action="{{ route('cart.remove', $item['product']) }}" method="POST" class="mt-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 text-sm hover:text-red-700">Remove</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Clear Cart Button -->
                        <div class="mt-4 text-center">
                            <form action="{{ route('cart.clear') }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition" onclick="return confirm('Are you sure you want to clear your cart?')">
                                    🗑️ Clear Cart
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                            <h2 class="text-xl font-semibold text-food-dark mb-6">Order Summary</h2>
                            
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span>${{ number_format($total, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Delivery Fee</span>
                                    <span>$5.00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Tax</span>
                                    <span>${{ number_format($total * 0.08, 2) }}</span>
                                </div>
                                <div class="border-t pt-3">
                                    <div class="flex justify-between font-semibold text-lg">
                                        <span>Total</span>
                                        <span class="text-food-primary">${{ number_format($total + 5 + ($total * 0.08), 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                @auth
                                    <a href="{{ route('orders.checkout') }}" class="w-full bg-food-primary text-white py-3 rounded-lg text-center font-semibold hover:bg-food-primary-dark transition block shadow-md">
                                        🚀 Proceed to Checkout
                                    </a>
                                @else
                                    <p class="text-center text-food-text-muted text-sm mb-3">Please login to proceed with checkout</p>
                                    <a href="{{ route('auth.login') }}" class="w-full bg-food-primary text-white py-3 rounded-lg text-center font-semibold hover:bg-food-primary-dark transition block shadow-md">
                                        🔑 Login to Checkout
                                    </a>
                                    <a href="{{ route('auth.register') }}" class="w-full bg-transparent border-2 border-food-primary text-food-primary py-3 rounded-lg text-center font-semibold hover:bg-food-cream transition block">
                                        📝 Create Account
                                    </a>
                                @endauth
                                
                                <a href="{{ route('products.index') }}" class="w-full bg-food-light-dark text-food-dark py-3 rounded-lg text-center font-semibold hover:bg-food-text-muted hover:text-white transition block">
                                    🍽️ Continue Shopping
                                </a>
                            </div>

                            <!-- Payment Icons -->
                            <div class="mt-6 pt-6 border-t border-food-light-dark text-center">
                                <p class="text-sm text-food-text-muted mb-2">We accept</p>
                                <div class="flex justify-center space-x-2">
                                    <span class="text-2xl">💳</span>
                                    <span class="text-2xl">💰</span>
                                    <span class="text-2xl">📱</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function increaseQuantity(productId) {
            const input = document.getElementById('quantity-' + productId);
            input.value = parseInt(input.value) + 1;
        }

        function decreaseQuantity(productId) {
            const input = document.getElementById('quantity-' + productId);
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }
    </script>
</x-main-layout>