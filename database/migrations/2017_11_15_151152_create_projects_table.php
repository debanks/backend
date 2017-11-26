<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tag', 64)->index();
            $table->string('name', 256);
            $table->string('employer');
            $table->string('color', 10)->nullable();
            $table->string('github')->nullable();
            $table->text('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('languages', 256);
            $table->mediumText('about')->nullable();
            $table->text('image_url')->nullable();
            $table->text('large_image_url')->nullable();
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
        Schema::dropIfExists('projects');
    }
}
