<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->get();
        $featuredProducts = Product::where('is_featured', true)
                                  ->where('is_active', true)
                                  ->with('category')
                                  ->limit(8)
                                  ->get();
        
        return view('home', compact('categories', 'featuredProducts'));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Here you could send an email, store in database, etc.
        // For now, we'll just redirect back with a success message
        
        return back()->with('success', 'Thank you for your message! We\'ll get back to you soon.');
    }
}
