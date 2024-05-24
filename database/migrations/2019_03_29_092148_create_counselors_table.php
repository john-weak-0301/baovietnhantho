<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCounselorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counselors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uid')->unique();

            $table->enum('gender', ['women', 'men', 'another'])->default('women')->index();
            $table->unsignedSmallInteger('province_id')->index();
            $table->unsignedSmallInteger('district_id')->index();

            $table->string('phone', 15)->nullable();
            $table->string('company_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('display_name')->nullable();
            $table->text('private_infomation')->nullable();
            $table->text('public_infomation')->nullable();

            $table->smallInteger('year_of_birth')->nullable();
            $table->string('job_title')->nullable();
            $table->integer('rate_value')->default(0);
            $table->string('avatar')->nullable(); // The image of relative URL.

            $table->timestamps();

            $table->index(['first_name', 'last_name']);
            $table->index(['province_id', 'district_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('counselors');
    }
}
