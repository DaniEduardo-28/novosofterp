<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCodeDateNullableGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_lots_group', function (Blueprint $table) {
            $table->string('code')->nullable()->change();
            $table->date('date_of_due')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_lots_group', function (Blueprint $table) {
            $table->string('code')->nullable(false)->change();
            $table->date('date_of_due')->nullable(false)->change();
        });
    }
}
