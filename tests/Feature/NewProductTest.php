<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Database\Factories\ProductFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class NewProductTest extends TestCase
{
    use RefreshDatabase;
    private $product1, $product2, $user;
    /**
     * A basic feature test example.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->authUser();
        $this->product1 = Product::create([
            'name' => 'Product One',
            'price' => 100.00,
            'user_id' => $this->user->id
        ]);
        $this->product2 = Product::create([
            'name' => 'Product Two',
            'price' => 200.00,
            'user_id' => $this->user->id
        ]);
        $this->product1->reviews()->create([
            'review' => 'Product One Review One'
        ]);
        $this->product1->reviews()->create([
            'review' => 'Product One Review Two'
        ]);
    }
    public function test_products_available(): void
    {
        // $this->withoutExceptionHandling();
        $response = $this->getJson(route('product.index'));

        $response->assertStatus(200);
        $this->assertEquals(2, count($response->json()));
        $this->assertEquals('Product One', $response->json()[0]['name']);
    }
    public function test_product_store_validation_failed(): void
    {
        $product = Product::factory()->make();

        $response = $this->postJson(route('product.store', [
            // 'name' => $product->name,
            // 'price' => $product->price
        ]));
        $response->assertJsonValidationErrors(['name', 'price']);
        $response->assertUnprocessable();
    }
    public function test_product_store_success(): void
    {
        $product = Product::factory()->make();

        $response = $this->postJson(route('product.store', [
            'name' => $product->name,
            'price' => $product->price,
            'user_id' => $this->user->id,
        ]));
        $response->assertCreated();
        $this->assertEquals('Product Stored!', $response->json()['message']);
        $this->assertDatabaseHas('products', ['name' => 'Product One']);
        $this->assertDatabaseHas('products', ['name' => 'Product Two']);
        $this->assertDatabaseHas('products', ['name' => $product->name]);
    }
    public function test_product_not_found(): void
    {
        $response = $this->getJson(route('product.show', [
            'product' => 200
        ]));
        $response->assertNotFound();
        $this->assertEquals('Product not found!', $response->json()['message']);
    }
    public function test_show_product(): void
    {
        $response = $this->getJson(route('product.show', [
            'product' => $this->product1->id
        ]));
        $response->assertOk();
        $this->assertEquals($this->product1->name, $response->json()['name']);
    }
    public function test_show_product_exists(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson(route('product.show', [
            'product' => $product->id
        ]));

        $response->assertOk();
        $this->assertEquals($response->json()['name'], $product->name);
    }
    public function test_update_product_success(): void
    {
        $response = $this->patchJson(route('product.update', [
            'product' => $this->product1->id,
            'name' => 'Product Three',
            // 'price' => 300.00
        ]));
        $response->assertOk()->json();
        $this->assertDatabaseHas('products', ['id' => $this->product1->id, 'name' => 'Product Three']);
        $this->assertEquals('Product Three', $response['product']['name']);
    }
    public function test_delete_product_success(): void
    {
        $response = $this->deleteJson(route('product.destroy', [
            'product' => $this->product1->id
        ]));
        // $this->product1->delete();
        // $response->assertNoContent();
        $this->assertDatabaseMissing('products', ['id' => $this->product1->id]);
        $this->assertDatabaseMissing('reviews', ['product_id' => $this->product1->id]);
    }
}
