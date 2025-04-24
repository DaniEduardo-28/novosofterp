<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddNewIdToQuotations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->unsignedInteger('patients_id')->nullable();
            $table->foreign('patients_id')->references('id')->on('patients');
            
            $table->unsignedInteger('cycles_id')->nullable();
            $table->foreign('cycles_id')->references('id')->on('cycles');

            $table->unsignedInteger('purchase_order_id')->nullable();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders');
            
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropForeign(['patients_id']);
            $table->dropColumn('patients_id');

            $table->dropForeign(['cycles_id']);
            $table->dropColumn('cycles_id');

            $table->dropForeign(['purchase_order_id']);
            $table->dropColumn('purchase_order_id');
        });
    }
}
