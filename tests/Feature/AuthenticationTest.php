<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function test_it_registers_user_successfully()
    {
        $user = ['email' => 'backend@multisyscorp.com', 'password' => 'test1234'];

        $this->json('POST', 'register', $user, ['Accept' => 'application/json'])
            ->assertJson([
               'message' => 'User successfully registered',
            ])
            ->assertStatus(201);
    }

    public function test_it_does_not_accept_duplicate_emails()
    {
        $user = new User();


        $user = ['email' => 'backend@multisyscorp.com', 'password' => 'test1234'];

    }
}
