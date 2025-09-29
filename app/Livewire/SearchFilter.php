<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class SearchFilter extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryId = '';
    public $minPrice = '';
    public $maxPrice = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $spiceLevel = '';
    public $isActive = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryId' => ['except' => ''],
        'minPrice' => ['except' => ''],
        'maxPrice' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'spiceLevel' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryId()
    {
        $this->resetPage();
    }

    public function updatingMinPrice()
    {
        $this->resetPage();
    }

    public function updatingMaxPrice()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->categoryId = '';
        $this->minPrice = '';
        $this->maxPrice = '';
        $this->spiceLevel = '';
        $this->sortBy = 'name';
        $this->sortDirection = 'asc';
        $this->resetPage();
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

    public function render()
    {
        $query = Product::query()
            ->with(['category', 'reviews'])
            ->where('is_active', $this->isActive);

        // Search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('ingredients', 'like', '%' . $this->search . '%');
            });
        }

        // Category filter
        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        // Price range filter
        if ($this->minPrice) {
            $query->where('price', '>=', $this->minPrice);
        }

        if ($this->maxPrice) {
            $query->where('price', '<=', $this->maxPrice);
        }

        // Spice level filter
        if ($this->spiceLevel) {
            $query->where('spice_level', $this->spiceLevel);
        }

        // Sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('livewire.search-filter', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}