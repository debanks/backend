<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tag', 64)->index();
            $table->string('name', 256);
            $table->text('description');
            $table->date('release_date');
            $table->string('system', 128);
            $table->mediumText('review')->nullable();
            $table->integer('score')->nullable();
            $table->text('image_url')->nullable();
            $table->text('large_image_url')->nullable();
            $table->tinyInteger('currently_playing')->default(0);
            $table->float('time_to_beat', 5,2)->nullable();
            $table->integer('playtime')->nullable();
            $table->tinyInteger('featured')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
