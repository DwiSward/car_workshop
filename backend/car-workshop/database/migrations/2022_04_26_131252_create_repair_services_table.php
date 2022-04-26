<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repair_services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('repair_id');
            $table->foreignUuid('service_id');
            $table->foreignUuid('mechanic_id');
            $table->decimal('price', 12, 2);
            $table->integer('status')->comment('0: new; 1: in progress; 2: done');
            $table->foreignUuid('repair_service_id')->comment('column for complaint; value from repair_services get complain');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('repair_id')->references('id')->on('repairs');
            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('mechanic_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repair_services');
    }
};
