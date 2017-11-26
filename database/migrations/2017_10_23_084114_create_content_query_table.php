<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentQueryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_query', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 64)->index();
            $table->integer('item_id')->unsigned();
            $table->tinyInteger('featured');
            $table->text('thumbnail_url')->nullable();
            $table->string('link_item_type')->index()->nullable();
            $table->integer('link_item_id')->index()->unsigned()->nullable();
            $table->string('link_item_name')->nullable();
            $table->string('tag')->nullable();
            $table->string('meta_data_1')->nullable();
            $table->string('meta_data_2')->nullable();
            $table->string('headline');
            $table->text('description')->nullable();
            $table->mediumText('content')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->index(['type', 'item_id']);
        });

        \DB::statement("ALTER TABLE content_query ADD FULLTEXT content_query_index(tag, headline, description, content, link_item_name)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_query');
    }
}
