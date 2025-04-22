<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantRenameTempColumnsInItems extends Migration
{
        /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->renameColumn('temp_inicial', 'temper_inicial');
            $table->renameColumn('temp_final', 'temper_final');
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
            $table->renameColumn('temper_inicial', 'temp_inicial');
            $table->renameColumn('temper_final', 'temp_final');
        });
    }
}

