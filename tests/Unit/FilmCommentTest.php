<?php

namespace Tests\Unit;

use App\Models\Film;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class FilmCommentTest extends TestCase
{
    use DatabaseMigrations;

    public function testFilmIdCantBeEmptyOrNull()
    {
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        $response =  $this->actingAs($user)->json('POST', 'api/film/create_comment', [
            'logo' => UploadedFile::fake()->image('avatar.jpg'),
            'content' => 'test comment',
            'film_id' => null
        ], ['HTTP_Authorization' => 'Bearer' . $token]);

        $response->assertStatus(404)
            ->assertJson([
                "message" => "No query results for model [App\Models\Film]."
            ]);

    }

    public function testFilmCommentCreation()
    {
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        $film =  $this->actingAs($user)->json('POST', 'api/film/create', [
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

        $decodedFilmData = json_decode($film->getContent());

        $response =  $this->actingAs($user)->json('POST', 'api/film/create_comment', [
            'logo' => UploadedFile::fake()->image('avatar.jpg'),
            'content' => 'test comment',
            'film_id' => $decodedFilmData->details->id
        ], ['HTTP_Authorization' => 'Bearer' . $token]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'details' => [
                    [
                        "id",
                        "commentable_type",
                        "commentable_id",
                        "comment",
                        "is_approved",
                        "user_id",
                        "created_at",
                        "updated_at",
                        "user" => [
                            "id",
                            "name",
                            "email",
                            "remember_token",
                            "created_at",
                            "updated_at"
                        ]
                    ]
                ]
            ]);
    }
}
