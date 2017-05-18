<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subreddit')->index();
            $table->string('thread_id')->index();
            $table->string('comment_id')->index();
            $table->string('parent_comment_id');
            $table->string('author');
            $table->integer('ups');
            $table->integer('downs');
            $table->integer('score');
            $table->text('body');
            $table->text('body_html');
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
        Schema::drop('comments');
    }
}
