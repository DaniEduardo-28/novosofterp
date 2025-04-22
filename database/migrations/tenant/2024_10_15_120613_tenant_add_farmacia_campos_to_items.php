<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddFarmaciaCamposToItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('concentracion')->nullable();
            $table->string('principio_activo')->nullable();
            $table->string('laboratorio')->nullable();
            $table->string('accion_farmacologica')->nullable();
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
            $table->dropColumn('concentracion');
            $table->dropColumn('principio_activo');
            $table->dropColumn('laboratorio');
            $table->dropColumn('accion_farmacologica');
        });
    }
}
