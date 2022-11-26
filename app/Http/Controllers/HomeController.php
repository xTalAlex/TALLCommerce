<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class HomeController extends Controller
{
    public function index()
    {
        $featured_categories = Category::featured()->take(5)->get();
        $featured_products = Product::featured()->where('quantity', '>', 0)->inRandomOrder()->take(1)->get();
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
        $collections = Collection::featured()->inRandomOrder()->take(3)->get();
        if ($collections->count())
            $collections = $collections->mapWithKeys(fn ($collection, $key) => [
                $key => [
                    'hero' => $collection->hero,
                    'url' => route('product.index', ['collection' => $collection->slug]),
                    'name' => $collection->name,
                    'description' => $collection->description
                ]
            ]);
        return view('home', compact('featured_categories', 'featured_products', 'brands', 'collections'));
    }
}
