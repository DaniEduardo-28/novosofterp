<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use phpDocumentor\Reflection\Types\Nullable;

class TenantRenameFieldsInPatients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->renameColumn('document_type_id', 'identity_document_type_id');
            $table->renameColumn('document_number', 'number');
            $table->renameColumn('first_name', 'name');

            $table->dropColumn('last_name');
 
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->renameColumn('identity_document_type_id', 'document_type_id');
            $table->renameColumn('number', 'document_number');
            $table->renameColumn('name', 'first_name');

            $table->string('last_name')->nullable();
 
        });
    }
}
