<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewPublishTest extends TestCase
{
    public $product1, $user;
    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->authUser();
        $this->product1 = Product::create([
            'name' => 'Product One',
            'price' => 100.00,
            'user_id' => $this->user->id
        ]);
    }
    public function test_review_publish_status_change_success(): void
    {
        $review = Review::factory()->make();
        $response = $this->postJson(route('product.reviews.store', [
            'product' => $this->product1->id,
        ]), [
            'review' => $review->review,
        ]);

        $response1 = $this->postJson(route('review.publish', $response->json()['id']), [
            'publish' => true,
        ]);

        $response1->assertOk();
    }
}
