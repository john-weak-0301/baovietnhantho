<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchsServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branchs_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('branchs_serviceables', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_service_id');
            $table->unsignedBigInteger('branch_id');
            $table->integer('order')->default(0);

            $table->unique(['branch_service_id', 'branch_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branchs_serviceables');
        Schema::dropIfExists('branchs_services');
    }
}
