<?php

namespace Modules\Digemid\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Digemid\Models\Cycles;
use Modules\Digemid\Http\Resources\CyclesCollection;
use Modules\Digemid\Http\Resources\CyclesResource;
use Modules\Digemid\Http\Requests\CyclesRequest;

class CyclesController extends Controller
{

    public function index()
    {
        return view('digemid::cycles.index');
    }


    public function columns()
    {
        return [
            'name' => 'Nombre',
        ];
    }

    public function records(Request $request)
    {
        $records = Cycles::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new CyclesCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = Cycles::findOrFail($id);

        return $record;
    }

    /**
     * Crea o edita un nuevo Ciclo.
     * El nombre de Ciclo debe ser único, por lo tanto se valida cuando el nombre existe.
     *
     * @param CyclesRequest $request
     *
     * @return array
     */
    public function store(CyclesRequest $request)
    {
        $id = (int)$request->input('id');
        $name = $request->input('name');
        $error = null;
        $cycles = null;
        if(!empty($name)){
            $cycles = Cycles::where('name', $name);
            if(empty($id)) {
                $cycles= $cycles->first();
                if (!empty($cycles)) {
                    $error = 'El nombre del ciclo ya existe';
                }
            }else{
                $cycles = $cycles->where('id','!=',$id)->first();
                if (!empty($cycles)) {
                    $error = 'El nombre del ciclo ya existe para otro registro';
                }
            }
        }
        $data = [
            'success' => false,
            'message' => $error,
            'data' => $cycles
        ];
        if(empty($error)){
            $cycles = Cycles::firstOrNew(['id' => $id]);
            $cycles->fill($request->all());
            $cycles->save();
            $data = [
                'success' => true,
                'message' => ($id)?'Ciclo editado con éxito':'Ciclo registrado con éxito',
                'data' => $cycles
            ];
        }
        return $data;

    }

    public function destroy($id)
    {
        try {

            $cycles = Cycles::findOrFail($id);
            $cycles->delete();

            return [
                'success' => true,
                'message' => 'Ciclo eliminada con éxito'
            ];

        } catch (\Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => "El ciclo esta siendo usado por otros registros, no puede eliminar"] : ['success' => false,'message' => "Error inesperado, no se pudo eliminar el ciclo"];

        }

    }




}
