<?php

namespace Modules\Digemid\Http\Requests;
use App\Models\Tenant\CatIdentityDocumentTypes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PatientsRequest extends FormRequest
{
     
    public function authorize()
    {
        return true; 
    }
 
    public function rules()
    {
        return [
            'identity_document_type_id' => [
                'nullable'
            ],
            'number' => [
                'nullable',
            ],
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'address' => [
                'nullable',
                'string',
                'max:255',
            ],
            'ubigeo' => [
                'nullable',
            ],
            'phone' => [
                'nullable',
                'numeric',
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
            ],
        ];

    }

    public function validated()
    {
        $validated = parent::validated();

        $validated['identity_document_type_id'] = $validated['identity_document_type_id'] ?? 1;
        $validated['number'] = $validated['number'] ?? '99999999';

        return $validated;
    }
}
