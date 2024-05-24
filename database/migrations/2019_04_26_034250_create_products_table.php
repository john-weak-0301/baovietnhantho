<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->text('subtitle')->nullable();
            $table->longText('content')->nullable();
            $table->longText('content_filtered')->nullable();
            $table->nestedSet();

            $table->integer('order')->nullable()->default(0);

            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type', 40)->nullable()->index();
            $table->unsignedBigInteger('category_id')->nullable()->index(); // deprecated
            $table->unsignedBigInteger('author_id')->nullable()->index();
            $table->string('status', 20)->default('pending')->index();

            $table->string('title')->index();
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->longText('content_filtered')->nullable();
            $table->jsonb('compare_attributes')->nullable();

            $table->string('image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->datetime('published_date')->nullable();
            $table->integer('order')->default(0);
            $table->jsonb('options')->nullable();
            $table->timestamps();

            $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
    }
}
