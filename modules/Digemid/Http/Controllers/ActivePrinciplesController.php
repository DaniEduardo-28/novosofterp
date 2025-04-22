<?php

namespace Modules\Digemid\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Digemid\Models\ActivePrinciples;
use Modules\Digemid\Http\Resources\ActivePrinciplesCollection;
use Modules\Digemid\Http\Resources\ActivePrinciplesResource;
use Modules\Digemid\Http\Requests\ActivePrinciplesRequest;

class ActivePrinciplesController extends Controller
{

    public function index()
    {
        return view('digemid::active_principles.index');
    }


    public function columns()
    {
        return [
            'name' => 'Nombre',
        ];
    }

    public function records(Request $request)
    {
        $records = ActivePrinciples::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new ActivePrinciplesCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = ActivePrinciples::findOrFail($id);

        return $record;
    }

    /**
     * Crea o edita un nuevo Principio Activo.
     * El nombre del Principio Activo debe ser único, por lo tanto se valida cuando el nombre existe.
     *
     * @param ActivePrinciplesRequest $request
     *
     * @return array
     */
    public function store(ActivePrinciplesRequest $request)
    {
        $id = (int)$request->input('id');
        $name = $request->input('name');
        $error = null;
        $active_principles = null;
        if(!empty($name)){
            $active_principles = ActivePrinciples::where('name', $name);
            if(empty($id)) {
                $active_principles= $active_principles->first();
                if (!empty($active_principles)) {
                    $error = 'El nombre del principio activo ya existe';
                }
            }else{
                $active_principles = $active_principles->where('id','!=',$id)->first();
                if (!empty($active_principles)) {
                    $error = 'El nombre del principio activo ya existe para otro registro';
                }
            }
        }
        $data = [
            'success' => false,
            'message' => $error,
            'data' => $active_principles
        ];
        if(empty($error)){
            $active_principles = ActivePrinciples::firstOrNew(['id' => $id]);
            $active_principles->fill($request->all());
            $active_principles->save();
            $data = [
                'success' => true,
                'message' => ($id)?'Principio Activo editado con éxito':'Principio Activo registrado con éxito',
                'data' => $active_principles
            ];
        }
        return $data;

    }

    public function destroy($id)
    {
        try {

            $active_principles = ActivePrinciples::findOrFail($id);
            $active_principles->delete();

            return [
                'success' => true,
                'message' => 'Principio Activo eliminada con éxito'
            ];

        } catch (\Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => "El principio activo esta siendo usado por otros registros, no puede eliminar"] : ['success' => false,'message' => "Error inesperado, no se pudo eliminar el principio activo"];

        }

    }




}
