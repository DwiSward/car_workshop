<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterNullableOnRepairServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('repair_services', function (Blueprint $table) {
            $table->foreignUuid('repair_service_id')->nullable()->change();
            $table->integer('status')->default(0)->change();
            $table->text('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('repair_services', function (Blueprint $table) {
            $table->foreignUuid('repair_service_id')->change();
            $table->integer('status')->change();
            $table->dropColumn('note');
        });
    }
}
