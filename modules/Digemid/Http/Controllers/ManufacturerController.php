<?php

namespace Modules\Digemid\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Digemid\Models\Manufacturer;
use Modules\Digemid\Http\Resources\ManufacturerCollection;
use Modules\Digemid\Http\Resources\ManufacturerResource;
use Modules\Digemid\Http\Requests\ManufacturerRequest;

class ManufacturerController extends Controller
{

    public function index()
    {
        return view('digemid::manufacturer.index');
    }


    public function columns()
    {
        return [
            'name' => 'Nombre',
        ];
    }

    public function records(Request $request)
    {
        $records = Manufacturer::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new ManufacturerCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = Manufacturer::findOrFail($id);

        return $record;
    }

    /**
     * Crea o edita un nuevo fabricante.
     * El nombre de fabricante debe ser único, por lo tanto se valida cuando el nombre existe.
     *
     * @param ManufacturerRequest $request
     *
     * @return array
     */
    public function store(ManufacturerRequest $request)
    {
        $id = (int)$request->input('id');
        $name = $request->input('name');
        $error = null;
        $manufacturer = null;
        if(!empty($name)){
            $manufacturer = Manufacturer::where('name', $name);
            if(empty($id)) {
                $manufacturer= $manufacturer->first();
                if (!empty($manufacturer)) {
                    $error = 'El nombre del fabricante ya existe';
                }
            }else{
                $manufacturer = $manufacturer->where('id','!=',$id)->first();
                if (!empty($manufacturer)) {
                    $error = 'El nombre del fabricante ya existe para otro registro';
                }
            }
        }
        $data = [
            'success' => false,
            'message' => $error,
            'data' => $manufacturer
        ];
        if(empty($error)){
            $manufacturer = Manufacturer::firstOrNew(['id' => $id]);
            $manufacturer->fill($request->all());
            $manufacturer->save();
            $data = [
                'success' => true,
                'message' => ($id)?'Fabricante editado con éxito':'Fabricante registrado con éxito',
                'data' => $manufacturer
            ];
        }
        return $data;

    }

    public function destroy($id)
    {
        try {

            $manufacturer = Manufacturer::findOrFail($id);
            $manufacturer->delete();

            return [
                'success' => true,
                'message' => 'Fabricante eliminado con éxito'
            ];

        } catch (\Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => "El fabricante esta siendo usado por otros registros, no puede eliminar"] : ['success' => false,'message' => "Error inesperado, no se pudo eliminar el fabricante"];

        }

    }




}
