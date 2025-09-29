<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)->get();
        
        $query = Product::with('category')->where('is_active', true);
        
        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        if ($request->has('search') && $request->search) {
            $query->where('name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('description', 'LIKE', '%' . $request->search . '%');
        }
        
        $products = $query->paginate(12);
        
        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load('category');
        $relatedProducts = Product::where('category_id', $product->category_id)
                                 ->where('id', '!=', $product->id)
                                 ->where('is_active', true)
                                 ->limit(4)
                                 ->get();
        
        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function category(Category $category)
    {
        $products = $category->products()
                            ->where('is_active', true)
                            ->paginate(12);
        
        return view('products.category', compact('category', 'products'));
    }
}
