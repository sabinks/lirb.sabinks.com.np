<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;
    public $product1, $product2, $user;
    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->authUser();
        $this->product1 = Product::create([
            'name' => 'Product One',
            'price' => 100.00,
            'user_id' => $this->user->id
        ]);
        $this->product1->reviews()->create([
            'review' => 'Product One Review One'
        ]);
        $this->product1->reviews()->create([
            'review' => 'Product One Review Two'
        ]);
        $this->product2 = Product::create([
            'name' => 'Product Two',
            'price' => 200.00,
            'user_id' => $this->user->id
        ]);
        $this->product2->reviews()->create([
            'review' => 'Product Two Review One'
        ]);
        $this->product2->reviews()->create([
            'review' => 'Product Two Review Two'
        ]);
    }
    /**
     * A basic feature test example.
     */
    public function test_product_reviews(): void
    {
        // $this->withoutExceptionHandling();
        $response = $this->getJson(route('product.reviews.index', [
            'product' => $this->product1->id,
            // 'product' => 0
        ]))->assertOk();
        $this->assertEquals(2, count($response->json()));
        $this->assertEquals('Product One Review One', $response->json()[0]['review']);
        // $response->assertStatus(200);  
    }

    public function test_review_store_validation_failed_test(): void
    {
        $response = $this->postJson(route('product.reviews.store', [
            'product' => $this->product1->id,
            'review' => ''
        ]));
        $response->assertJsonValidationErrors(['review']);
        $response->assertUnprocessable();
    }

    public function test_review_store_sucess(): void
    {
        $review = Review::factory()->make();
        $response = $this->postJson(route('product.reviews.store', [
            'product' => $this->product1->id,
            'review' => $review->review
        ]));
        $response->assertCreated();
        $this->assertEquals($response->json()['review'], $review->review);
        $this->assertDatabaseHas('reviews', ['review' => $review->review]);
    }

    public function test_show_review(): void
    {
        $review = Review::factory()->make();
        $response = $this->postJson(route('product.reviews.store', [
            'product' => $this->product1->id,
            'review' => $review->review
        ]));
        $response = $this->getJson(route('reviews.show', $response->json()['id']));
        $response->assertOk();
        $this->assertEquals($response->json(['review']), $review->review);
    }

    public function test_update_review(): void
    {
        $review = Review::factory()->make();
        $response = $this->postJson(route('product.reviews.store', [
            'product' => $this->product1->id,
            'review' => $review->review
        ]));
        $response = $this->patchJson(route('reviews.update', $response->json()['id']), [
            'review' => 'Product One Review Five'
        ]);
        $response->assertOk();
        $this->assertDatabaseHas('reviews', ['review' => 'Product One Review Five']);
    }

    public function test_review_delete(): void
    {
        $review = Review::factory()->make();
        $response = $this->postJson(route('product.reviews.store', [
            'product' => $this->product1->id,
            'review' => $review->review
        ]));
        $response1 = $this->deleteJson(route('reviews.destroy', [
            'product' => $this->product1->id,
            'review' => $response->json()['id']
        ]));
        $response1->assertNoContent();
        $this->assertDatabaseMissing('reviews', ['id' => $response->json()['id']]);
    }
}
