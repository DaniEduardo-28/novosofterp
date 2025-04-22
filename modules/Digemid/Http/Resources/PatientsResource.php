<?php

namespace Modules\Digemid\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'identity_document_type_id' => $this->identity_document_type_id,
            'identity_document_type' => $this->identity_document_types->description ?? '',
            'number' => $this->number,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'address' => $this->address,
            'ubigeo' => $this->ubigeo,
            'phone' => $this->phone,
            'email' => $this->email
        ];
    }
}