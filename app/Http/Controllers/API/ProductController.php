<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     */
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
        return response()->json([
            'message' => 'Product Stored!',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $product)
    {
        $prod = Product::find($product);
        if (!$prod) {
            return response()->json([
                'message' => 'Product not found!',
            ], 404);
        }
        return response()->json($prod, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $product)
    {
        $request->validate([
            'name' => 'string|max:255',
            'price' => ''
        ]);
        $prod = Product::find($product);
        if (!$prod) {
            return response()->json([
                'message' => 'Product not found!',
            ], 404);
        }
        $input = $request->only(['name', 'price']);
        foreach ($input as $key => $value) {
            $prod[$key] = $value;
        }
        $prod->update();
        return response()->json([
            'product' => $prod
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $product)
    {
        $prod = Product::find($product);
        if (!$prod) {
            return response()->json([
                'message' => 'Product not found!',
            ], 404);
        }
        $prod->reviews()->delete();
        $prod->delete();
        return response('', 204);
    }
}
