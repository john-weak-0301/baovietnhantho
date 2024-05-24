<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('namespace')->default('');
            $table->string('name')->index();
            $table->string('slug')->index();
            $table->text('description')->nullable();
            $table->integer('order')->nullable()->default(0);
            $table->timestamps();

            $table->unique(['namespace', 'slug']);
        });

        Schema::create('categoriables', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id'); // TODO: rename to `id`.
            $table->morphs('categoriable');
            $table->integer('order')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categoriables');
        Schema::dropIfExists('categories');
    }
}
