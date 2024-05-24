<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_categories', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->string('icon', 100)->nullable();
            $table->boolean('show_in_menu')->default(true);

            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('service_service_category', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('service_category_id');

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('service_category_id')->references('id')->on('service_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_service_category');
        Schema::dropIfExists('service_categories');
    }
}
