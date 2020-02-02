<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        DB::table('users')->insertGetId([
            'name'     => $faker->firstName,
            'email'    => $faker->freeEmail,
            'password' => bcrypt('123456')
        ]);

        DB::table('users')->insertGetId([
            'name'     => 'test',
            'email'    => 'test@gmail.com',
            'password' => bcrypt('123456')
        ]);
    }
}
