<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserAuthTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Migrates the database and set the mailer to 'pretend'.
     * This will cause the tests to run quickly.
     *
     */


    /**
     * Disable middleware for the test.
     *
     * @param  string|array|null  $middleware
     * @return $this
     */
    public function withoutMiddleware($middleware = null)
    {
        if (is_null($middleware)) {
            $this->app->instance('middleware.disable', true);

            return $this;
        }

        foreach ((array) $middleware as $abstract) {
            $this->app->instance($abstract, new class {
                public function handle($request, $next)
                {
                    return $next($request);
                }
            });
        }

        return $this;
    }


    public function testUserCanLoginWithCorrectCredentials()
    {
        \DB::table('users')->insertGetId([
            'name'     => 'test',
            'email'    => 'test@gmail.com',
            'password' => bcrypt('123456')
        ]);

        $response = $this->post('api/login', [
            'email' => 'test@gmail.com',
            'password' => '123456',
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
        ]);
    }

    public function testUserCannotLoginWithIncorrectPassword()
    {
        \DB::table('users')->insertGetId([
            'name'     => 'test',
            'email'    => 'test@gmail.com',
            'password' => bcrypt('123456')
        ]);

        $response = $this->post('api/login', [
            'email' => 'test@gmail.com',
            'password' => 'invalid-password',
        ]);

        $response
            ->assertStatus(401)
            ->assertJson([
                "message" => "Invalid login credential."
            ]);
    }

}
