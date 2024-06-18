<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;
    public $product1, $product2;
    public function setUp(): void
    {
        parent::setUp();
        $this->product1 = Product::create([
            'name' => 'Product One',
            'price' => 100.00
        ]);
        $this->product1->reviews()->create([
            'review' => 'Product One Review One'
        ]);
        $this->product1->reviews()->create([
            'review' => 'Product One Review Two'
        ]);
        $this->product2 = Product::create([
            'name' => 'Product Two',
            'price' => 200.00
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
        $response = $this->getJson(route('reviews.index', [
            'product' => $this->product1->id,
            // 'product' => 0
        ]))->assertOk();
        $this->assertEquals(2, count($response->json()));
        $this->assertEquals('Product One Review One', $response->json()[0]['review']);
        // $response->assertStatus(200);  
    }
}
