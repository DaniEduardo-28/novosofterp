<?php

namespace Modules\Digemid\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Digemid\Models\Laboratory;
use Modules\Digemid\Http\Resources\LaboratoryCollection;
use Modules\Digemid\Http\Resources\LaboratoryResource;
use Modules\Digemid\Http\Requests\LaboratoryRequest;

class LaboratoryController extends Controller
{

    public function index()
    {
        return view('digemid::laboratory.index');
    }


    public function columns()
    {
        return [
            'name' => 'Nombre',
        ];
    }

    public function records(Request $request)
    {
        $records = Laboratory::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new LaboratoryCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = Laboratory::findOrFail($id);

        return $record;
    }

    /**
     * Crea o edita un nuevo laboratorio.
     * El nombre de laboratorio debe ser único, por lo tanto se valida cuando el nombre existe.
     *
     * @param LaboratoryRequest $request
     *
     * @return array
     */
    public function store(LaboratoryRequest $request)
    {
        $id = (int)$request->input('id');
        $name = $request->input('name');
        $error = null;
        $laboratory = null;
        if(!empty($name)){
            $laboratory = Laboratory::where('name', $name);
            if(empty($id)) {
                $laboratory= $laboratory->first();
                if (!empty($laboratory)) {
                    $error = 'El nombre del laboratorio ya existe';
                }
            }else{
                $laboratory = $laboratory->where('id','!=',$id)->first();
                if (!empty($laboratory)) {
                    $error = 'El nombre del laboratorio ya existe para otro registro';
                }
            }
        }
        $data = [
            'success' => false,
            'message' => $error,
            'data' => $laboratory
        ];
        if(empty($error)){
            $laboratory = Laboratory::firstOrNew(['id' => $id]);
            $laboratory->fill($request->all());
            $laboratory->save();
            $data = [
                'success' => true,
                'message' => ($id)?'Laboratorio editado con éxito':'Laboratorio registrado con éxito',
                'data' => $laboratory
            ];
        }
        return $data;

    }

    public function destroy($id)
    {
        try {

            $laboratory = Laboratory::findOrFail($id);
            $laboratory->delete();

            return [
                'success' => true,
                'message' => 'Laboratorio eliminada con éxito'
            ];

        } catch (\Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => "El laboratorio esta siendo usado por otros registros, no puede eliminar"] : ['success' => false,'message' => "Error inesperado, no se pudo eliminar el laboratorio"];

        }

    }




}
