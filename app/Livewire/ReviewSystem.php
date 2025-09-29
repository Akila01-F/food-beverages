<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ReviewSystem extends Component
{
    use WithPagination;

    public Product $product;
    public $rating = 5;
    public $comment = '';
    public $showReviewForm = false;
    public $editingReviewId = null;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $filterRating = '';

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|min:10|max:1000',
    ];

    protected $messages = [
        'rating.required' => 'Please select a rating.',
        'rating.min' => 'Rating must be at least 1 star.',
        'rating.max' => 'Rating cannot exceed 5 stars.',
        'comment.required' => 'Please write a review comment.',
        'comment.min' => 'Review must be at least 10 characters.',
        'comment.max' => 'Review cannot exceed 1000 characters.',
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function toggleReviewForm()
    {
        if (!Auth::check()) {
            $this->dispatch('show-login-modal');
            return;
        }

        $this->showReviewForm = !$this->showReviewForm;
        if (!$this->showReviewForm) {
            $this->resetForm();
        }
    }

    public function submitReview()
    {
        if (!Auth::check()) {
            $this->dispatch('show-login-modal');
            return;
        }

        $this->validate();

        // Check if user already reviewed this product
        $existingReview = Review::where('user_id', Auth::id())
                              ->where('product_id', $this->product->id)
                              ->first();

        if ($existingReview && !$this->editingReviewId) {
            $this->addError('general', 'You have already reviewed this product. You can edit your existing review.');
            return;
        }

        if ($this->editingReviewId) {
            // Update existing review
            $review = Review::find($this->editingReviewId);
            if ($review && $review->user_id === Auth::id()) {
                $review->update([
                    'rating' => $this->rating,
                    'comment' => $this->comment,
                ]);
                $this->dispatch('review-updated');
            }
        } else {
            // Create new review
            Review::create([
                'user_id' => Auth::id(),
                'product_id' => $this->product->id,
                'rating' => $this->rating,
                'comment' => $this->comment,
            ]);
            $this->dispatch('review-created');
        }

        $this->resetForm();
        $this->showReviewForm = false;
        $this->editingReviewId = null;

        // Refresh product to update average rating
        $this->product->refresh();
    }

    public function editReview($reviewId)
    {
        $review = Review::find($reviewId);
        
        if ($review && $review->user_id === Auth::id()) {
            $this->editingReviewId = $reviewId;
            $this->rating = $review->rating;
            $this->comment = $review->comment;
            $this->showReviewForm = true;
        }
    }

    public function deleteReview($reviewId)
    {
        $review = Review::find($reviewId);
        
        if ($review && $review->user_id === Auth::id()) {
            $review->delete();
            $this->dispatch('review-deleted');
            $this->product->refresh();
        }
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function updatingFilterRating()
    {
        $this->resetPage();
    }

    private function resetForm()
    {
        $this->rating = 5;
        $this->comment = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        $query = $this->product->reviews()
            ->with('user')
            ->where('is_approved', true);

        // Filter by rating
        if ($this->filterRating) {
            $query->where('rating', $this->filterRating);
        }

        // Sort reviews
        $query->orderBy($this->sortBy, $this->sortDirection);

        $reviews = $query->paginate(10);

        // Get user's existing review if logged in
        $userReview = null;
        if (Auth::check()) {
            $userReview = Review::where('user_id', Auth::id())
                              ->where('product_id', $this->product->id)
                              ->first();
        }

        // Calculate rating distribution
        $ratingDistribution = Review::where('product_id', $this->product->id)
            ->where('is_approved', true)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        return view('livewire.review-system', [
            'reviews' => $reviews,
            'userReview' => $userReview,
            'ratingDistribution' => $ratingDistribution,
        ]);
    }
}