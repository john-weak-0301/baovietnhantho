<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePopupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('popups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('title')->index();
            $table->string('status', 20)->index()->default('pending'); // ['pending', 'publish']
            $table->integer('order')->nullable()->default(0);

            $table->string('position')->default('center');
            $table->string('layout')->default('default');
            $table->string('width')->nullable();
            $table->string('action_type')->nullable();
            $table->mediumText('action_payload')->nullable();

            $table->longText('html_content')->nullable();
            $table->longText('css_content')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('popups');
    }
}
