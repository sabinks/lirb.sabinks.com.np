<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Inertia\Testing\AssertableInertia;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Assert;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_products_page_available(): void
    {
        $response = $this->get('/products');
        $response->assertStatus(200);
    }

    // public function test_products_page_contains_no_products()
    // {
    // Product::create([
    //     'name' => 'Product One',
    //     'price' => 100.20
    // ]);
    // $response = $this->get('/products');
    // $response->assertSee("No products");
    // $response->assertStatus(200);
    //     $this->get('/products')
    //         ->assertOk()
    //         ->assertInertia(
    //             fn (AssertableInertia $page) => $page
    //                 ->component('Products')
    //                 // ->has('products')
    //                 ->where('products',  [])
    //         );
    // }
    public function test_validate_product_form()
    {
        $response = $this->get('/products/create');

        $this->followingRedirects()
            ->post('/products', [])
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Product')
                    ->where('errors.name', 'The name field is required.')
                    ->where('errors.price', 'The price field is required.')
            );
    }
    public function test_create_product()
    {
        $response = $this->get('/products/create');
        $this->followingRedirects();
        $this->post('/products', [
            'name' => 'Product One',
            'price' => 100.20
        ])
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Products')
            );
    }
    // public function test_products_exists()
    // {
    //     $product1 = Product::create([
    //         'name' => 'Product One',
    //         'price' => 100.00
    //     ]);
    //     $product2 = Product::create([
    //         'name' => 'Product Two',
    //         'price' => 200.00
    //     ]);
    //     $this->followingRedirects()
    //         ->get('/products')
    //         ->assertOk()
    //         ->assertInertia(
    //             fn (AssertableInertia $page) =>
    //             $page->component('Products')
    //                 ->has('products', 2)
    //                 ->has('products.0', fn (AssertableInertia $page) => $page->where('name', 'Product One'))
    //             // ->has('products.2', fn (AssertableInertia $page) => $page->where('name', 'Product Two'))
    //         );
    // }
    public function test_show_product()
    {
        $product = Product::create([
            'name' => 'Product One',
            'price' => 200.20
        ]);

        $this->followingRedirects()
            ->get('/products/' . $product->id)
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) =>
                $page->component('Product')
                    ->where('status', 'Product Found!')
            );
    }
    public function test_update_product_with_price_field_missing()
    {
        $product = Product::create([
            'name' => 'Product One',
            'price' => 200.20
        ]);
        $this->get('/products/' . $product->id . '/edit');
        $this->followingRedirects();
        $this->put('/products/' . $product->id, [
            'name' => 'Product Two',
        ])
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) =>
                $page->component('Update')
                    ->where('errors.price', 'The price field is required.')
            );
    }
    public function test_update_product()
    {
        $product = Product::create([
            'name' => 'Product One',
            'price' => 200.20
        ]);
        $this->get('/products/' . $product->id . '/edit');
        $this->followingRedirects();
        $this->put('/products/' . $product->id, [
            'name' => 'Product Two',
            'price' => 300.00
        ])
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) =>
                $page->component('Update')
                    ->where('status', 'Product Updated!')
            );
    }
    public function test_delete_product()
    {
        $product = Product::create([
            'name' => 'Product One',
            'price' => 200.20
        ]);

        $this->followingRedirects()
            ->delete('/products/' . $product->id)
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) =>
                $page->component('Products')
                    ->where('status', 'Product Deleted!')
            );
    }
}
