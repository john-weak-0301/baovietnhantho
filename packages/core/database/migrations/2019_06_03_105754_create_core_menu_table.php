<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoreMenuTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::dropIfExists('menu');

        Schema::create('menu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label');
            $table->string('title')->nullable();
            $table->string('slug');
            $table->string('robot')->nullable();
            $table->string('style')->nullable();
            $table->string('target')->nullable();
            $table->boolean('auth')->default(false);
            $table->integer('parent')->default(0);
            $table->integer('sort')->default(0);
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('menu');
    }
}
