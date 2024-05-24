<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branchs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->string('type', 40);
            $table->string('address');
            $table->unsignedInteger('ward_code');
            $table->unsignedInteger('province_code');
            $table->unsignedInteger('district_code');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('fax_number')->nullable();
            $table->jsonb('working_days')->nullable();
            $table->text('additional_info')->nullable();

            $table->index('address');
            $table->index(['ward_code', 'province_code', 'district_code', 'address']);
            $table->unique(['latitude', 'longitude']);

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
        Schema::dropIfExists('branchs');
    }
}
