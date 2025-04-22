<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddTemperatureFieldsToItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->boolean('consider_temperature')->default(false);
            $table->decimal('temp_inicial', 5, 2)->nullable();
            $table->decimal('temp_final', 5, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {

            $table->dropColumn('consider_temperature');
            $table->dropColumn('temp_inicial');
            $table->dropColumn('temp_final');
        });
    }
}
