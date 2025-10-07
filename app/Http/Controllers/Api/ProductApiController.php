<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductApiController extends Controller
{
    /**
     * Display a listing of products with filtering and pagination
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with('category')->active();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'ILIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'ILIKE', "%{$searchTerm}%")
                  ->orWhere('ingredients', 'ILIKE', "%{$searchTerm}%");
            });
        }

        // Category filter
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        // Featured filter
        if ($request->has('featured') && $request->boolean('featured')) {
            $query->featured();
        }

        // In stock filter
        if ($request->has('in_stock') && $request->boolean('in_stock')) {
            $query->where('stock_quantity', '>', 0);
        }

        // Price range filter
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Spice level filter
        if ($request->has('spice_level') && !empty($request->spice_level)) {
            $query->where('spice_level', $request->spice_level);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSortFields = ['name', 'price', 'created_at', 'stock_quantity', 'preparation_time'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination
        $perPage = min($request->get('per_page', 15), 50); // Max 50 items per page
        $products = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'message' => 'Products retrieved successfully',
            'data' => ProductResource::collection($products->items()),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
            ]
        ]);
    }

    /**
     * Display the specified product
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $product = Product::with('category')->active()->findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Product retrieved successfully',
                'data' => new ProductResource($product)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
                'data' => null
            ], 404);
        }
    }

    /**
     * Get products by category
     * 
     * @param string $categoryId
     * @param Request $request
     * @return JsonResponse
     */
    public function getByCategory(string $categoryId, Request $request): JsonResponse
    {
        try {
            $category = Category::findOrFail($categoryId);
            
            $query = Product::with('category')
                ->where('category_id', $categoryId)
                ->active();

            // Apply same filters as index method
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'ILIKE', "%{$searchTerm}%")
                      ->orWhere('description', 'ILIKE', "%{$searchTerm}%")
                      ->orWhere('ingredients', 'ILIKE', "%{$searchTerm}%");
                });
            }

            if ($request->has('featured') && $request->boolean('featured')) {
                $query->featured();
            }

            if ($request->has('in_stock') && $request->boolean('in_stock')) {
                $query->where('stock_quantity', '>', 0);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'name');
            $sortOrder = $request->get('sort_order', 'asc');
            
            $allowedSortFields = ['name', 'price', 'created_at', 'stock_quantity'];
            if (in_array($sortBy, $allowedSortFields)) {
                $query->orderBy($sortBy, $sortOrder);
            }

            // Pagination
            $perPage = min($request->get('per_page', 15), 50);
            $products = $query->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'message' => "Products in category '{$category->name}' retrieved successfully",
                'data' => ProductResource::collection($products->items()),
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ],
                'meta' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found',
                'data' => null
            ], 404);
        }
    }

    /**
     * Get featured products
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getFeatured(Request $request): JsonResponse
    {
        $query = Product::with('category')->active()->featured();
        
        $limit = min($request->get('limit', 10), 20); // Max 20 featured items
        $products = $query->limit($limit)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Featured products retrieved successfully',
            'data' => ProductResource::collection($products)
        ]);
    }

    /**
     * Search products
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $searchTerm = $request->get('q', '');
        
        if (empty($searchTerm)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Search term is required',
                'data' => []
            ], 400);
        }

        $query = Product::with('category')->active()
            ->where(function ($q) use ($searchTerm) {
                $q->where('name', 'ILIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'ILIKE', "%{$searchTerm}%")
                  ->orWhere('ingredients', 'ILIKE', "%{$searchTerm}%");
            });

        $perPage = min($request->get('per_page', 15), 50);
        $products = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'message' => "Search results for '{$searchTerm}'",
            'data' => ProductResource::collection($products->items()),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'search_term' => $searchTerm,
            ]
        ]);
    }
}
