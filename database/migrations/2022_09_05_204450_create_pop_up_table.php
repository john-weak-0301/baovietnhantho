<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePopUpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pop_ups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title1');
            $table->string('title2')->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->default(1);
            $table->string('image', 500);
            $table->string('cta_link', 500);
            $table->string('cta_text')->nullable();
            $table->string('layout')->enum('left', 'right');
            $table->text('pages')->nullable();
            $table->integer('show_all')->default(0);
            $table->integer('show_products')->default(0);
            $table->integer('show_posts')->default(0);
            $table->integer('show_pages')->default(0);
            $table->integer('show_service')->default(0);
            $table->integer('show_expert')->default(0);
            $table->integer('show_home_page')->default(0);
            $table->text('show_more_links')->nullable();
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
        Schema::dropIfExists('pop_ups');
    }
}
