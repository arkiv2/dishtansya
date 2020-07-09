<?php

namespace Tests\Feature;

use App\Product;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use ProductTableSeeder;
use Tests\TestCase;

class OrderTest extends TestCase
{
    public function setupWorld()
    {
        $this->seed(ProductTableSeeder::class);
        return factory(User::class)->create([
            'email' => 'backend@multisyscorp.com',
            'password' => bcrypt('test1234'),
        ]);
    }

    /**
     * @group order
     */
    public function test_users_can_successfully_create_an_order()
    {
        $user = $this->setupWorld();
        $this->actingAs($user, 'api');

        $orderData = ['product_id' => 1, 'quantity' => 2];
        $this->json('POST', 'order', $orderData, ['Accept' => 'application/json'])
            ->assertJson([
                'message' => 'You have successfully ordered this product'
            ])
            ->assertStatus(201);
        $this->assertDatabaseCount('orders', 1);
    }

    /**
     * @group order
     */
    public function test_it_decrements_product_stock_upon_successful_order()
    {
        $user = $this->setupWorld();
        $this->actingAs($user, 'api');
        $quantity = 2;
        $product = Product::where('id', 1)->first();
        $stock = $product->available_stock;

        $orderData = ['product_id' => $product->id, 'quantity' => $quantity];
        $this->json('POST', 'order', $orderData, ['Accept' => 'application/json'])
            ->assertJson([
                'message' => 'You have successfully ordered this product'
            ])
            ->assertStatus(201);
        $this->assertDatabaseCount('orders', 1);
        $product = Product::where('id', 1)->first();
        $this->assertEquals($stock - $quantity, Product::where('id', 1)->first()->available_stock);
    }

    /**
     * @group order
     */
    public function test_it_does_not_allow_orders_higher_than_available_stock()
    {
        $user = $this->setupWorld();
        $this->actingAs($user, 'api');
        $quantity = 105;
        $product = Product::where('id', 1)->first();
        $stock = $product->available_stock;

        $orderData = ['product_id' => $product->id, 'quantity' => $quantity];
        $this->json('POST', 'order', $orderData, ['Accept' => 'application/json'])
            ->assertJson([
                'message' => 'Failed to order this product due to unavailability of the stock'
            ])
            ->assertStatus(400);
        $this->assertDatabaseCount('orders', 0);
        $product = Product::where('id', 1)->first();
        $this->assertEquals(100, Product::where('id', 1)->first()->available_stock);
    }

    /**
     * @group order
     */
    public function test_it_does_not_allow_guest_to_order()
    {
        $orderData = ['product_id' => 1, 'quantity' => 5];
        $this->json('POST', 'order', $orderData, ['Accept' => 'application/json'])
            ->assertJson([
                'message' => 'Unauthenticated.'
            ])
            ->assertStatus(401);
    }
}
