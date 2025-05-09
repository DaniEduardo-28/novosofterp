<?php

namespace Modules\Dispatch\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Person;
use Modules\ApiPeruDev\Data\ServiceData;
use Modules\Dispatch\Http\Requests\DispatchAddressRequest;
use Modules\Dispatch\Models\DispatchAddress;

class DispatchAddressController extends Controller
{
    public function tables()
    {
        $locations = func_get_locations();

        return [
            'locations' => $locations
        ];
    }

    public function store(DispatchAddressRequest $request)
    {
        $id = $request->input('id');
        $record = DispatchAddress::query()->firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();

        return [
            'success' => true,
            'id' => $record->id
        ];
    }

    public function destroy($id)
    {
        DispatchAddress::query()
            ->find($id)
            ->update([
                'is_active' => false,
            ]);

        return [
            'success' => true,
            'message' => 'Dirección eliminada con éxito'
        ];
    }

    public function getOptions($person_id)
    {

        return DispatchAddress::query()
            ->where('person_id', $person_id)
            ->get()
            ->transform(function ($row) {

                $locations = func_get_locations();
                $department_id = $row->location_id[0];
                $province_id = $row->location_id[1];
                $district_id = $row->location_id[2];
                $filtered_department = null;
                $filtered_province = null;
                $filtered_district = null;
                foreach ($locations as $department) {
                    if ($department['value'] == $department_id) {
                        $filtered_department =  $department["label"];
                        foreach ($department['children'] as $province) {
                            if ($province['value'] == $province_id) {
                                $filtered_province = $province;
                                foreach ($province['children'] as $district) {
                                    if ($district['value'] == $district_id) {
                                        $filtered_district = $district;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }

                return [
                    'id' => $row->id,
                    'location_id' => $row->location_id,
                    'address' => " - " . $row->address . " , " . $filtered_district["label"] . " - " . $filtered_province["label"] . " - " .  $filtered_department,
                ];
            });
    }

    public function searchAddress($person_id)
    {
        $person = Person::query()->find($person_id);
        if ($person->identity_document_type_id === '1') {
            $type = 'dni';
        } elseif ($person->identity_document_type_id === '6') {
            $type = 'ruc';
        } else {
            return [
                'success' => false,
                'message' => 'No se encontró dirección'
            ];
        }
        return (new ServiceData())->service($type, $person->number);
    }
}
