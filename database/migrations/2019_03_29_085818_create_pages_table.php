<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->index(); // The class name of the page type.
            $table->string('status', 20)->index()->default('pending'); // ['pending', 'publish']

            $table->string('title')->index();
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->longText('content_filtered')->nullable();

            $table->integer('order')->nullable()->default(0);
            $table->jsonb('options')->nullable();
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
        Schema::dropIfExists('pages');
    }
}
