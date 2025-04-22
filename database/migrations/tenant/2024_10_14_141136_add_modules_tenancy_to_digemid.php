<?php

use App\Models\Tenant\Module;
use Modules\LevelAccess\Models\ModuleLevel;
use Illuminate\Database\Migrations\Migration;
/**
 * Class AddModulesDigemid
 */
class AddModulesTenancyToDigemid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $e = new Module([
            'value'       => 'pharmacy',
            'description' => 'Farmacia',
            'sort'        => 2,
            'order_menu'  => 2
        ]);
        $e->save();
        $moduleLevels = [
            ['value' => 'pharmacy_pos', 'description' => 'Venta Rápida'],
            ['value' => 'pharmacy_caja', 'description' => 'Caja Chica'],
            ['value' => 'pharmacy_products', 'description' => 'Productos'],
            ['value' => 'pharmacy_report', 'description' => 'Reporte DIGEMID'],
        ];

        foreach ($moduleLevels as $level) {
            $q = new ModuleLevel($level);
            $q->setModuleId($e->id);
            $q->save();
        }
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar niveles de módulos por su valor
        $moduleLevels = [
            'pharmacy_pos',
            'pharmacy_caja',
            'pharmacy_products',
            'pharmacy_report',
        ];

        foreach ($moduleLevels as $levelValue) {
            ModuleLevel::where('value', $levelValue)->delete();
        }

        // Eliminar el módulo 'pharmacy'
        Module::where('value', 'pharmacy')->delete();
    }
}
