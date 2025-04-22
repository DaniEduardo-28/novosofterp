<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddPdfFieldsToItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('brochure_pdf')->nullable();
            $table->string('analisis_pdf')->nullable();
            $table->string('bpa_pdf')->nullable();
            $table->string('registro_sanitario_pdf')->nullable();
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

            $table->dropColumn('brochure_pdf');
            $table->dropColumn('analisis_pdf');
            $table->dropColumn('bpa_pdf');
            $table->dropColumn('registro_sanitario_pdf');
        });
    }
}
