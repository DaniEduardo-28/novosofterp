<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use phpDocumentor\Reflection\Types\Nullable;

class TenantCreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('document_type_id');
            $table->foreign('document_type_id')->references('id')->on('cat_identity_document_types');
            $table->string('document_number');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('address')->nullable();
            $table->string('ubigeo', 6)->nullable();
            $table->string('phone', 9)->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
