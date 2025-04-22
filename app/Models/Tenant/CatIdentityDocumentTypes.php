<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class CatIdentityDocumentTypes extends ModelTenant
{
    protected $table = 'cat_identity_document_types';

    protected $fillable = [ 
        'id',
        'active',
        'description',
    ];
    public $timestamps = false;
}
