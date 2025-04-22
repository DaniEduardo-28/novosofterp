<?php

namespace Modules\Digemid\Models;

use App\Models\Tenant\ModelTenant;

class PharmacologicalAction extends ModelTenant
{

    // Nombre correcto de la tabla
    protected $table = 'pharmacological_action';
    
    protected $fillable = [ 
        'id',
        'name',
        'status',
    ];
 

    public function scopeFilterForTables($query)
    {
        return $query->select('id', 'name')->orderBy('name');
    }

    public function getRowResourceApi()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    
    /**
     * 
     * Data para filtros - select
     *
     * @return array
     */
    public static function getDataForFilters()
    {
        return self::select(['id', 'name'])->orderBy('name')->get();
    }

}