<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFortniteUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('fortnite_users', function (Blueprint $table) {

            $table->increments('id');
            $table->string('name');
            $table->integer('solo_matches')->default(0);
            $table->integer('solo_wins')->default(0);
            $table->integer('solo_kills')->default(0);
            $table->float('solo_mmr', 9, 3)->default(0.0);
            $table->integer('duo_matches')->default(0);
            $table->integer('duo_wins')->default(0);
            $table->integer('duo_kills')->default(0);
            $table->float('duo_mmr', 9, 3)->default(0.0);
            $table->integer('squad_matches')->default(0);
            $table->integer('squad_wins')->default(0);
            $table->integer('squad_kills')->default(0);
            $table->float('squad_mmr', 9, 3)->default(0.0);
            $table->tinyInteger('collect')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {

        Schema::dropIfExists('fortnite_users');
    }
}
