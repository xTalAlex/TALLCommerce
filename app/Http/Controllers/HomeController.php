<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Province;
use App\Models\Collection;
use Illuminate\Http\Request;
use App\Models\ShippingPrice;
use Illuminate\Support\Facades\App;

class HomeController extends Controller
{
    public function index()
    {
        $featured_categories = Category::with('media')->featured()->take(3)->get();
        // $featured_products = Product::where('quantity', '>', 0)->inRandomOrder()->take(15)->get();
        $featured_products = Product::with('media')->withCount('paidOrders')
            ->orderBy('featured','desc')->orderBy('paid_orders_count','desc')
            ->take(10)->get();
        $brands = Brand::whereHas(
            'media',
            fn ($query) =>
            $query->whereCollectionName('logo')
        )->get();
        if ($brands->count())
            $brands = $brands->mapWithKeys(fn ($brand, $key) => [
                $key => [
                    'logo' => $brand->logo_gray,
                    'url' => route('product.index', ['brand' => $brand->slug])
                ]
            ]);
        $featured_collection = Collection::with('media')->featured()->whereHas('media')->latest()->first();
        return view('home', compact('featured_categories', 'featured_products', 'brands', 'featured_collection'));
    }

    public function aboutUs()
    {
        return view('about-us');
    }

    public function delivery()
    {
        $provinces = Province::all();
        $shippingPrices = ShippingPrice::active()->get();
        
        return view('delivery', compact('provinces','shippingPrices'));
    }

    public function info()
    {
        return view('info');
    }

    public function contactUs()
    {
        return view('contact-us');
    }
}
