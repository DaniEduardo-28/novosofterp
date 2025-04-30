<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantInsertCartaGarantiaIntoCatIdentityDocumentTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('cat_identity_document_types')->insert([
            'id' => 'F', // El siguiente ID disponible
            'active' => 1,
            'description' => 'Carta de Garantía',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('cat_identity_document_types')->where('id', 'F')->delete();
    }
}
