<?php

namespace Modules\Item\Models;

use App\Models\Tenant\ModelTenant;

class Subgroup extends ModelTenant
{

    protected $fillable = [ 
        'id',
        'name',
        'category_id',
        'status',
    ];
 
    public function scopeFilterForTables($query)
    {
        return $query->select('id', 'name')->orderBy('name');
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault([
            'id' => '',
            'name' => ''
        ]);
    }

}