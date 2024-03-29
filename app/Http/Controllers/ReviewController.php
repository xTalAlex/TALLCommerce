<?php

namespace App\Http\Controllers;

use App\Models\{Review,Product};
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        $this->authorize('create', [Review::class, $product]);

        $validated = $request->validate([
            'rating' => 'required|numeric|min:0|max:5',
            'description' => 'nullable|max:500',
        ]);

        // if($product->defaultVariant)
        //     $product = $product->defaultVariant;

        $product->reviews()->create([
            'user_id' => auth()->user()->id,
            'rating' => $validated['rating'],
            'description' => $validated['description'],
            'approved' => config('custom.reviews.approved_by_default') ?? false,
        ]);

        $tab = 1;

        return redirect()->route('product.show', compact('product','tab'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        //
    }
}
