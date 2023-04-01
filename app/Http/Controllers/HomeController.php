<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Collection;

class HomeController extends Controller
{
    public function index()
    {
        $featured_categories = Category::with('media')->featured()->take(3)->get();
        $featured_products = Product::with('media')->featured()->take(10)->get();
        $featured_brands = Brand::logoed()->featured()->get();
        $featured_collections = Collection::with('media')->whereHas('media')->featured()->take(3)->get();
        return view('home', compact('featured_categories', 'featured_products', 'featured_brands', 'featured_collections'));
    }
}
