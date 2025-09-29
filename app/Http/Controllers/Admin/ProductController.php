<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request): View
    {
        $query = Product::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $products = $query->paginate(15)->appends($request->all());
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create(): View
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'discounted_price' => 'nullable|numeric|min:0|lt:price',
                'stock_quantity' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'sku' => 'nullable|string|max:50|unique:products,sku',
                'is_active' => 'boolean',
                'is_featured' => 'boolean',
                'ingredients' => 'nullable|string',
                'spice_level' => 'nullable|in:mild,medium,hot',
                'preparation_time' => 'nullable|integer|min:1',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Generate SKU if not provided
            if (empty($validated['sku'])) {
                $validated['sku'] = 'PROD-' . strtoupper(Str::random(8));
            }

            // Handle boolean fields (checkboxes)
            $validated['is_active'] = $request->has('is_active');
            $validated['is_featured'] = $request->has('is_featured');

            // Handle image uploads
            $imagesPaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    if ($image->isValid()) {
                        $path = $image->store('products', 'public');
                        $imagesPaths[] = $path;
                    }
                }
                if (!empty($imagesPaths)) {
                    $validated['images'] = $imagesPaths;
                }
            }

            $product = Product::create($validated);

            return redirect()
                ->route('admin.products.show', $product)
                ->with('success', 'Product created successfully!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Please check the form for errors.');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'An error occurred while creating the product: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified product
     */
    public function show(Product $product): View
    {
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product): View
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'nullable|string|max:50|unique:products,sku,' . $product->id,
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'ingredients' => 'nullable|string',
            'spice_level' => 'nullable|in:mild,medium,hot',
            'preparation_time' => 'nullable|integer|min:1',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle boolean fields (checkboxes)
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        // Handle image uploads
        if ($request->hasFile('images')) {
            // Delete old images if replacing
            if ($product->images) {
                foreach ($product->images as $imagePath) {
                    Storage::disk('public')->delete($imagePath);
                }
            }

            $imagesPaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $imagesPaths[] = $path;
            }
            $validated['images'] = $imagesPaths;
        }

        $product->update($validated);

        return redirect()
            ->route('admin.products.show', $product)
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product): RedirectResponse
    {
        // Delete associated images
        if ($product->images) {
            foreach ($product->images as $imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Bulk actions for products
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'products' => 'required|array',
            'products.*' => 'exists:products,id'
        ]);

        $products = Product::whereIn('id', $validated['products']);
        $count = $products->count();

        switch ($validated['action']) {
            case 'activate':
                $products->update(['is_active' => true]);
                $message = "{$count} product(s) activated successfully!";
                break;
            case 'deactivate':
                $products->update(['is_active' => false]);
                $message = "{$count} product(s) deactivated successfully!";
                break;
            case 'delete':
                // Delete images for all products being deleted
                foreach ($products->get() as $product) {
                    if ($product->images) {
                        foreach ($product->images as $imagePath) {
                            Storage::disk('public')->delete($imagePath);
                        }
                    }
                }
                $products->delete();
                $message = "{$count} product(s) deleted successfully!";
                break;
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', $message);
    }
}