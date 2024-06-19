<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Review;
use Database\Factories\ReviewFactory;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class ProductUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_product_reviews_is_instanace_of(): void
    {
        $product1 = Product::create([
            'name' => 'Product One',
            'price' => 100.00
        ]);
        $product1->reviews()->create([
            'review' => 'Product One Review One'
        ]);
        $product1->reviews()->create([
            'review' => 'Product One Review Two'
        ]);
        $this->assertInstanceOf(Collection::class, $product1->reviews);
        $this->assertInstanceOf(Review::class, $product1->reviews()->first());
        $this->assertTrue(true);
    }
    public function test_reviews_is_instance_of_product(): void
    {
        $product1 = Product::create([
            'name' => 'Product One',
            'price' => 100.00
        ]);
        $product1->reviews()->create([
            'review' => 'Product One Review One'
        ]);
        $product1->reviews()->create([
            'review' => 'Product One Review Two'
        ]);
        $review = Review::factory()->create(['product_id' => $product1->id]);
        $this->assertInstanceOf(Product::class, $review->product);
    }
}
