<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subreddit')->index();
            $table->string('thread_id');
            $table->string('title');
            $table->string('author');
            $table->integer('comments');
            $table->integer('ups');
            $table->integer('downs');
            $table->integer('score');
            $table->string('url');
            $table->tinyInteger('spoiler');
            $table->tinyInteger('over18');
            $table->float('weight');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('threads');
    }
}
