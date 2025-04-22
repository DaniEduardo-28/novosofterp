<?php

namespace Modules\Digemid\Models;

use App\Models\Tenant\ModelTenant;

class ActivePrinciples extends ModelTenant
{

    // Nombre correcto de la tabla
    protected $table = 'active_principles';
    
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