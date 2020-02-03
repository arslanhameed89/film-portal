<?php

use App\Models\Film;
use App\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;

class FilmsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1,20) as $index) {
            $dt = Carbon::now();
            $dateNow = $dt->toDateTimeString();

            $last_id = DB::table('films')->insertGetId([
                'name' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                'slug' => $faker->slug,
                'release_date' => $dateNow,
                'rating' => $faker->numberBetween(1,5),
                'description' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
                'country_id' => $faker->numberBetween(1,8),
                'photo' => '',
                'price' => $faker->randomNumber(2),
                'created_at'        => $dateNow,
                'updated_at'        => $dateNow
            ]);

            DB::table('film_genres')->insertGetId([
                'name'    => $faker->words(3)[0],
                'film_id' => $last_id
            ]);

            $user = User::find(1);
            $film = Film::find($last_id);
            $film->commentAsUser($user, $faker->sentence($nbWords = 6, $variableNbWords = true));
        }
    }
}
