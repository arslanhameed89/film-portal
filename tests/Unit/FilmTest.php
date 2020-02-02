<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class FilmTest extends TestCase
{
    use DatabaseMigrations;

    public function testInvalidData()
    {
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        $response =  $this->actingAs($user)->json('POST', 'api/film/create', [
            'logo' => UploadedFile::fake()->image('avatar.jpg'),
            'film' => '{"date":"2020-02-02","dateFormatted":null,"menu1":false,"rating":1,"genre":[],"description":null,"releaseDate":false,"name":null,"price":null,"country_id":null,"logo":null}',
        ], ['HTTP_Authorization' => 'Bearer' . $token]);

        $response->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid."
            ]);

    }

    public function testFilmCreationWithImageUpload()
    {
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        $response =  $this->actingAs($user)->json('POST', 'api/film/create', [
            'logo' => UploadedFile::fake()->image('avatar.jpg'),
            'film' => json_encode([
                "date" => "2020-02-02",
                "dateFormatted" => "02/02/2020",
                "menu1" => false,
                "rating" => 1,
                "genre" => ["12"],
                "description" => "qwe",
                "releaseDate" => false,
                "name" => "first",
                "price" => "12",
                "country_id" => 4
            ])
        ], ['HTTP_Authorization' => 'Bearer' . $token]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'details' => [
                    "name",
                    "slug",
                    "release_date" ,
                    "rating",
                    "description",
                    "country_id",
                    "photo",
                    "price",
                    "created_at",
                    "updated_at",
                    "id"
                ]
            ]);
    }
}
