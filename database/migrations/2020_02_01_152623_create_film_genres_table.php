<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateFilmGenresTable.
 */
class CreateFilmGenresTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('film_genres', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('film_id');
            $table->timestamps();

            $table->foreign('film_id')->references('id')->on('films');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::disableForeignKeyConstraints();
        Schema::drop('film_genres');
        Schema::enableForeignKeyConstraints();
	}
}
