<?php

namespace Modules\Digemid\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Digemid\Models\Patients;

class PatientsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function ($row, $key) {

            return [
                'id' => $row->id,
                'identity_document_type' => $row->identity_document_type->description ?? '', // Obtiene la descripción o cadena vacía
                'number' => $row->number,
                'name' => $row->name,
                'last_name' => $row->last_name,
                'address' => $row->address,
                'ubigeo' => $row->ubigeo,
                'phone' => $row->phone,
                'email' => $row->email,
            ];
        });
    }
}
