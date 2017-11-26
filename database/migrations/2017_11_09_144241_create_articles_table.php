<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('tag', 64)->index();
            $table->string('item_type')->index()->nullable();
            $table->integer('item_id')->index()->unsigned()->nullable();
            $table->string('item_name')->nullable();
            $table->tinyInteger('featured')->default(0);
            $table->string('title');
            $table->text('thumbnail_url')->nullable();
            $table->text('link_url')->nullable();
            $table->tinyInteger('sticky')->default(0);
            $table->text('summary')->nullable();
            $table->mediumText('content');
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
        Schema::dropIfExists('articles');
    }
}
