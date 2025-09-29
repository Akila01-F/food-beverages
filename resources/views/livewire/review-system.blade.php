<div class="space-y-6">
    <!-- Review Summary -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Overall Rating -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Reviews</h3>
                <div class="flex items-center space-x-4">
                    <div class="text-4xl font-bold text-yellow-500">{{ number_format($product->average_rating, 1) }}</div>
                    <div>
                        <div class="flex text-yellow-400 mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($product->average_rating))
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <div class="text-sm text-gray-600">{{ $product->reviews_count }} reviews</div>
                    </div>
                </div>
            </div>

            <!-- Rating Distribution -->
            <div>
                <h4 class="font-medium text-gray-900 mb-3">Rating Breakdown</h4>
                @for($i = 5; $i >= 1; $i--)
                    @php
                        $count = $ratingDistribution[$i] ?? 0;
                        $percentage = $product->reviews_count > 0 ? ($count / $product->reviews_count) * 100 : 0;
                    @endphp
                    <div class="flex items-center text-sm mb-2">
                        <span class="w-8">{{ $i }}★</span>
                        <div class="flex-1 bg-gray-200 rounded-full h-2 mx-3">
                            <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="w-8 text-right">{{ $count }}</span>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Write Review Button -->
        @auth
            @if(!$userReview)
                <div class="mt-6 pt-6 border-t">
                    <button 
                        wire:click="toggleReviewForm"
                        class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors"
                    >
                        Write a Review
                    </button>
                </div>
            @else
                <div class="mt-6 pt-6 border-t">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">You have already reviewed this product</span>
                        <button 
                            wire:click="editReview({{ $userReview->id }})"
                            class="text-blue-600 hover:text-blue-800 text-sm underline"
                        >
                            Edit Review
                        </button>
                    </div>
                </div>
            @endif
        @else
            <div class="mt-6 pt-6 border-t">
                <p class="text-gray-600">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 underline">Sign in</a> 
                    to write a review
                </p>
            </div>
        @endauth
    </div>

    <!-- Review Form -->
    @if($showReviewForm)
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                {{ $editingReviewId ? 'Edit Your Review' : 'Write a Review' }}
            </h3>
            
            <form wire:submit="submitReview">
                <!-- Rating -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <div class="flex space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                            <button 
                                type="button"
                                wire:click="$set('rating', {{ $i }})"
                                class="text-2xl {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 transition-colors"
                            >
                                ★
                            </button>
                        @endfor
                    </div>
                    @error('rating') 
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Comment -->
                <div class="mb-4">
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Your Review</label>
                    <textarea 
                        id="comment"
                        wire:model="comment"
                        rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Share your experience with this product..."
                    ></textarea>
                    @error('comment') 
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex space-x-3">
                    <button 
                        type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove>
                            {{ $editingReviewId ? 'Update Review' : 'Submit Review' }}
                        </span>
                        <span wire:loading>Submitting...</span>
                    </button>
                    
                    <button 
                        type="button"
                        wire:click="toggleReviewForm"
                        class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 transition-colors"
                    >
                        Cancel
                    </button>
                </div>

                @error('general') 
                    <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </form>
        </div>
    @endif

    <!-- Reviews List -->
    <div class="space-y-4">
        <!-- Filter and Sort Controls -->
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <label class="text-sm font-medium text-gray-700">Filter by rating:</label>
                    <select wire:model.live="filterRating" class="text-sm border border-gray-300 rounded-md px-3 py-1">
                        <option value="">All Ratings</option>
                        <option value="5">5 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="2">2 Stars</option>
                        <option value="1">1 Star</option>
                    </select>
                </div>
                
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-700">Sort by:</span>
                    <button 
                        wire:click="sortBy('created_at')"
                        class="text-sm px-3 py-1 rounded-md border {{ $sortBy === 'created_at' ? 'bg-blue-100 border-blue-300 text-blue-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' }}"
                    >
                        Newest
                        @if($sortBy === 'created_at')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </button>
                    <button 
                        wire:click="sortBy('rating')"
                        class="text-sm px-3 py-1 rounded-md border {{ $sortBy === 'rating' ? 'bg-blue-100 border-blue-300 text-blue-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' }}"
                    >
                        Rating
                        @if($sortBy === 'rating')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </button>
                </div>
            </div>
        </div>

        <!-- Reviews -->
        @forelse($reviews as $review)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-medium">
                            {{ substr($review->user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">{{ $review->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $review->created_at->format('M j, Y') }}</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        
                        @auth
                            @if($review->user_id === auth()->id())
                                <div class="flex space-x-2">
                                    <button 
                                        wire:click="editReview({{ $review->id }})"
                                        class="text-blue-600 hover:text-blue-800 text-sm"
                                    >
                                        Edit
                                    </button>
                                    <button 
                                        wire:click="deleteReview({{ $review->id }})"
                                        class="text-red-600 hover:text-red-800 text-sm"
                                        onclick="return confirm('Are you sure you want to delete this review?')"
                                    >
                                        Delete
                                    </button>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
                
                <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No reviews yet</h3>
                <p class="mt-2 text-sm text-gray-500">Be the first to share your experience with this product!</p>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($reviews->hasPages())
            <div class="mt-6">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>