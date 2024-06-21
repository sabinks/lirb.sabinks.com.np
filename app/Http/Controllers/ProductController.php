<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return Inertia::render('Products')->with(['products' => $products]);
    }
    public function create()
    {
        return Inertia::render('Product');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required',
            'user_id' => 'required|string'
        ]);
        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'user_id' => $request->user_id
        ]);
        return redirect('/products');
    }
    public function show(Product $product)
    {
        return Inertia::render('Product')->with([
            'product' => $product,
            'status' => 'Product Found!'
        ]);
    }
    public function edit(Product $product)
    {
        return Inertia::render('Update')->with([
            'product' => $product,
        ]);
    }
    public function update(Product $product, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required'
        ]);
        $product->name = $request->name;
        $product->price = $request->price;
        $product->update();

        return Inertia::render('Update')->with([
            'product' => $product,
            'status' => 'Product Updated!'
        ]);
    }
    public function destroy(string $product)
    {
        $product = Product::find($product);
        // $product->reviews()->delete();
        $product->delete();
        return Inertia::render('Products')->with([
            'status' => 'Product Deleted!'
        ]);
    }
}
