<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BasicTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_homepage_contains_word(): void
    {
        $response = $this->get('/');
        $response->assertSee('Laravel');
        // $response->assertStatus(200);
    }
    public function test_login_page()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }
}
