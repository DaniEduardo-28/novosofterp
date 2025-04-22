<?php

namespace Modules\Digemid\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Digemid\Models\Origin;
use Modules\Digemid\Http\Resources\OriginCollection;
use Modules\Digemid\Http\Resources\OriginResource;
use Modules\Digemid\Http\Requests\OriginRequest;

class OriginController extends Controller
{

    public function index()
    {
        return view('digemid::origin.index');
    }


    public function columns()
    {
        return [
            'name' => 'Nombre',
        ];
    }

    public function records(Request $request)
    {
        $records = Origin::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new OriginCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = Origin::findOrFail($id);

        return $record;
    }

    /**
     * Crea o edita una nueva procedencia.
     * El nombre de la procedencia debe ser único, por lo tanto se valida cuando el nombre existe.
     *
     * @param OriginRequest $request
     *
     * @return array
     */
    public function store(OriginRequest $request)
    {
        $id = (int)$request->input('id');
        $name = $request->input('name');
        $error = null;
        $origin = null;
        if(!empty($name)){
            $origin = Origin::where('name', $name);
            if(empty($id)) {
                $origin= $origin->first();
                if (!empty($origin)) {
                    $error = 'El nombre de la procedencia ya existe';
                }
            }else{
                $origin = $origin->where('id','!=',$id)->first();
                if (!empty($origin)) {
                    $error = 'El nombre de la procedencia ya existe para otro registro';
                }
            }
        }
        $data = [
            'success' => false,
            'message' => $error,
            'data' => $origin
        ];
        if(empty($error)){
            $origin = Origin::firstOrNew(['id' => $id]);
            $origin->fill($request->all());
            $origin->save();
            $data = [
                'success' => true,
                'message' => ($id)?'Procedencia editada con éxito':'Procedencia registrada con éxito',
                'data' => $origin
            ];
        }
        return $data;

    }

    public function destroy($id)
    {
        try {

            $origin = Origin::findOrFail($id);
            $origin->delete();

            return [
                'success' => true,
                'message' => 'Procedencia eliminada con éxito'
            ];

        } catch (\Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => "La procedencia esta siendo usado por otros registros, no puede eliminar"] : ['success' => false,'message' => "Error inesperado, no se pudo eliminar la procedencia"];

        }

    }




}
