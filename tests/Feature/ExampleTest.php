<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test the shop catalog page returns a successful response.
     */
    public function test_shop_catalog_returns_a_successful_response(): void
    {
        $response = $this->get('/shop');

        $response->assertStatus(200);
        $response->assertSee('All Products');
    }

    /**
     * Test category filter on shop catalog returns a successful response.
     */
    public function test_shop_category_filter_returns_a_successful_response(): void
    {
        $response = $this->get('/shop?category=Electronics');

        $response->assertStatus(200);
    }
}
