<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRegistrationTest extends TestCase
{
    use DatabaseMigrations;


    public function testUserRegistration()
    {

        $response = $this->post('api/register', [
            'name' => "asdf",
            'email'=> "muaqah@gmail.com",
            'password'=> "123456",
            'password_confirmation'=> "123456"
        ]);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'token',
            'user' => [
                "id",
                "name",
                "email",
                "created_at",
                "updated_at",
            ]
        ]);;
    }
}
