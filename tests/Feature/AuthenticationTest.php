<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function setupUser()
    {
        $user = factory(User::class)->create([
            'email' => 'backend@multisyscorp.com',
            'password' => bcrypt('test1234'),
        ]);
    }

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
        $this->setupUser();

        $duplicatedEmail = ['email' => 'backend@multisyscorp.com', 'password' => 'test1234'];

        $this->json('POST', 'register', $duplicatedEmail, ['Accept' => 'application/json'])
            ->assertJson([
                'message' => 'Email already taken'
            ])
            ->assertStatus(400);
    }

    public function test_it_can_login_users_successfully()
    {
        $this->setupUser();

        $login = ['email' => 'backend@multisyscorp.com', 'password' => 'test1234'];

        $this->json('POST', 'login', $login, ['Accept' => 'application/json'])
            ->assertJsonStructure([
                'access_token',
            ])
            ->assertStatus(201);
    }

    public function test_it_does_not_login_users_with_incorrect_email()
    {
        $this->setupUser();

        $login = ['email' => 'frontend@multisyscorp.com', 'password' => 'test1234'];

        $this->json('POST', 'login', $login, ['Accept' => 'application/json'])
            ->assertJson([
               'message' => 'Invalid credentials',
            ])
            ->assertStatus(401);
    }

    public function test_it_does_not_login_users_with_incorrect_password()
    {
        $this->setupUser();

        $login = ['email' => 'backend@multisyscorp.com', 'password' => 'test6666'];

        $this->json('POST', 'login', $login, ['Accept' => 'application/json'])
            ->assertJson([
                'message' => 'Invalid credentials',
            ])
            ->assertStatus(401);
    }

    public function test_it_locks_account_for_five_minutes_after_five_failed_attempts()
    {
        $this->setupUser();

        $login = ['email' => 'backend@multisyscorp.com', 'password' => 'test6666'];
        $this->json('POST', 'login', $login, ['Accept' => 'application/json'])
            ->assertJson([
                'message' => 'Invalid credentials',
            ])
            ->assertStatus(401);
        $this->json('POST', 'login', $login, ['Accept' => 'application/json'])
            ->assertJson([
                'message' => 'Invalid credentials',
            ])
            ->assertStatus(401);
        $this->json('POST', 'login', $login, ['Accept' => 'application/json'])
            ->assertJson([
                'message' => 'Invalid credentials',
            ])
            ->assertStatus(401);
        $this->json('POST', 'login', $login, ['Accept' => 'application/json'])
            ->assertJson([
                'message' => 'Invalid credentials',
            ])
            ->assertStatus(401);
        $this->json('POST', 'login', $login, ['Accept' => 'application/json'])
            ->assertJson([
                'message' => 'Invalid credentials',
            ])
            ->assertStatus(401);
        $this->json('POST', 'login', $login, ['Accept' => 'application/json'])
            ->assertJson([
                'message' => 'Your Account is locked for 5 minutes',
            ])
            ->assertStatus(401);

    }
}
