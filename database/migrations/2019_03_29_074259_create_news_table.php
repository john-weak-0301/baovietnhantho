<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('author_id')->nullable()->index();
            $table->string('type', 40)->default('news')->index();
            $table->string('status', 20)->default('pending')->index();

            $table->string('title')->index();
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->longText('content_filtered')->nullable();

            $table->string('image')->nullable();
            $table->string('image_slider')->nullable();
            $table->boolean('in_slider')->default(false)->index();
            $table->boolean('is_featured')->default(false)->index();

            $table->datetime('published_date')->nullable();
            $table->integer('order')->default(0);
            $table->jsonb('options')->nullable();
            $table->timestamps();

            $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}
