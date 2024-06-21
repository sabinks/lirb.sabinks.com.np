<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $product)
    {
        $reviews =  $product ? Review::whereProductId($product)->get()->toArray() : Review::all();
        return response($reviews, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $product, Request $request)
    {
        $request->validate([
            'review' => 'required|max:255|string'
        ]);
        $review = Review::create([
            'review' => $request->review,
            'product_id' => $product
        ]);
        return response($review, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $review)
    {
        $review = Review::find($review);
        if (!$review) {
            return response('Review not found!', 404);
        }
        return response($review, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $review)
    {
        $request->validate([
            'review' => 'required|max:255|string'
        ]);
        $review = Review::find($review);
        if (!$review) {
            return response('Review not found!', 404);
        }
        $review->review = $request->review;
        $review->update();
        return response('Review updated!', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return response('', 204);
    }

    public function reviewPublish(string $review, Request $request)
    {
        $review = Review::find($review);
        if (!$review) {
            return response('Review not found!', 404);
        }
        $review->publish = $request->publish;

        $review->update();

        return response($review);
    }
}
