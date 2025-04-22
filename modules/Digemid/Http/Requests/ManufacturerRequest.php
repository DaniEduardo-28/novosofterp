<?php

namespace Modules\Digemid\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ManufacturerRequest extends FormRequest
{
     
    public function authorize()
    {
        return true; 
    }
 
    public function rules()
    { 
        
        $id = $this->input('id');
        return [
             
            'name' => [
                'required',
            ],
            'status' => [
                'required',
            ]
        ];

    }
}
