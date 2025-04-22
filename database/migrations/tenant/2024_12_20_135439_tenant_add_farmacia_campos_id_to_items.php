<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddFarmaciaCamposIdToItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->unsignedInteger('act_principles_id')->nullable();
            $table->foreign('act_principles_id')->references('id')->on('active_principles');
            
            $table->unsignedInteger('laboratory_id')->nullable();
            $table->foreign('laboratory_id')->references('id')->on('laboratory');
            
            $table->unsignedInteger('pharma_action_id')->nullable();
            $table->foreign('pharma_action_id')->references('id')->on('pharmacological_action');
            
            $table->unsignedInteger('subgroup_id')->nullable();
            $table->foreign('subgroup_id')->references('id')->on('subgroups');
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
            $table->dropForeign(['act_principles_id']);
            $table->dropColumn('act_principles_id');

            $table->dropForeign(['laboratory_id']);
            $table->dropColumn('laboratory_id');

            $table->dropForeign(['pharma_action_id']);
            $table->dropColumn('pharma_action_id');

            $table->dropForeign(['subgroup_id']);
            $table->dropColumn('subgroup_id');
        });
    }
}
