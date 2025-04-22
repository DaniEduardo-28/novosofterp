<?php

namespace Modules\Digemid\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Digemid\Models\PharmacologicalAction;
use Modules\Digemid\Http\Resources\PharmacologicalActionCollection;
use Modules\Digemid\Http\Resources\PharmacologicalActionResource;
use Modules\Digemid\Http\Requests\PharmacologicalActionRequest;

class PharmacologicalActionController extends Controller
{

    public function index()
    {
        return view('digemid::pharmacological_action.index');
    }


    public function columns()
    {
        return [
            'name' => 'Nombre',
        ];
    }

    public function records(Request $request)
    {
        $records = PharmacologicalAction::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new PharmacologicalActionCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = PharmacologicalAction::findOrFail($id);

        return $record;
    }

    /**
     * Crea o edita una nueva Acción Farmacológica.
     * El nombre de la Acción Farmacológica debe ser único, por lo tanto se valida cuando el nombre existe.
     *
     * @param PharmacologicalActionRequest $request
     *
     * @return array
     */
    public function store(PharmacologicalActionRequest $request)
    {
        $id = (int)$request->input('id');
        $name = $request->input('name');
        $error = null;
        $pharmacological_action = null;
        if(!empty($name)){
            $pharmacological_action = PharmacologicalAction::where('name', $name);
            if(empty($id)) {
                $pharmacological_action= $pharmacological_action->first();
                if (!empty($pharmacological_action)) {
                    $error = 'El nombre de la acción farmacológica ya existe';
                }
            }else{
                $pharmacological_action = $pharmacological_action->where('id','!=',$id)->first();
                if (!empty($pharmacological_action)) {
                    $error = 'El nombre de la acción farmacológica ya existe para otro registro';
                }
            }
        }
        $data = [
            'success' => false,
            'message' => $error,
            'data' => $pharmacological_action
        ];
        if(empty($error)){
            $pharmacological_action = PharmacologicalAction::firstOrNew(['id' => $id]);
            $pharmacological_action->fill($request->all());
            $pharmacological_action->save();
            $data = [
                'success' => true,
                'message' => ($id)?'Acción farmacológica editado con éxito':'Acción farmacológica registrado con éxito',
                'data' => $pharmacological_action
            ];
        }
        return $data;

    }

    public function destroy($id)
    {
        try {

            $pharmacological_action = PharmacologicalAction::findOrFail($id);
            $pharmacological_action->delete();

            return [
                'success' => true,
                'message' => 'Acción Farmacológica eliminada con éxito'
            ];

        } catch (\Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => "La acción farmacológica esta siendo usado por otros registros, no puede eliminar"] : ['success' => false,'message' => "Error inesperado, no se pudo eliminar la acción farmacológica"];

        }

    }




}
