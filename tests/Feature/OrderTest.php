<?php

namespace Tests\Feature;

use App\User;
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

//    public function test_it_does_not_allow_guest_to_order()
//    {
//
//    }
}
