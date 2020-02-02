<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class FilmListingTest extends TestCase
{
    use DatabaseMigrations;

    public function testAllFilmsData()
    {

        $response =  $this->get( 'api/guest/film/all');

        $response->assertStatus(200);

    }

    public function testGetFilmBySlug()
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

        $response =  $this->actingAs($user)->post('api/film/getFilmBySlug', [
            'slug' => $decodedFilmData->details->slug,
            'auth' => null
        ], ['HTTP_Authorization' => 'Bearer' . $token]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'details' => [
                    "id",
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
                    "genres" => [
                        [
                            "id",
                            "name",
                            "film_id",
                            "created_at",
                            "updated_at"
                        ]
                    ]
                ]
            ]);
    }
}
