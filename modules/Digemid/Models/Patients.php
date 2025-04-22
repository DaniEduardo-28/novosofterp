<?php

namespace Modules\Digemid\Models;

use App\Models\Tenant\Catalogs\IdentityDocumentType;

use App\Models\Tenant\ModelTenant;

class Patients extends ModelTenant
{

    protected $with = [
        'identity_document_type'
    ];

    protected $fillable = [
        'id',
        'identity_document_type_id',
        'number',
        'name',
        'last_name',
        'address',
        'ubigeo',
        'phone',
        'email',
    ];

    public function scopeFilterForTables($query)
    {
        return $query->select('id', 'name', 'number')->orderBy('name');
    }

    public function documentType()
    {
        return $this->belongsTo(IdentityDocumentType::class)->withDefault([
            'id' => '',
            'description' => ''
        ]);
    }


    public function identity_document_type()
    {
        return $this->belongsTo(IdentityDocumentType::class, 'identity_document_type_id');
    }
}
