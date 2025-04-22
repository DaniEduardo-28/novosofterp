<?php

namespace Modules\Digemid\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Digemid\Models\Importer;
use Modules\Digemid\Http\Resources\ImporterCollection;
use Modules\Digemid\Http\Resources\ImporterResource;
use Modules\Digemid\Http\Requests\ImporterRequest;

class ImporterController extends Controller
{

    public function index()
    {
        return view('digemid::importer.index');
    }


    public function columns()
    {
        return [
            'name' => 'Nombre',
        ];
    }

    public function records(Request $request)
    {
        $records = Importer::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new ImporterCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = Importer::findOrFail($id);

        return $record;
    }

    /**
     * Crea o edita un nuevo Importador.
     * El nombre de Importador debe ser único, por lo tanto se valida cuando el nombre existe.
     *
     * @param ImporterRequest $request
     *
     * @return array
     */
    public function store(ImporterRequest $request)
    {
        $id = (int)$request->input('id');
        $name = $request->input('name');
        $error = null;
        $importer = null;
        if(!empty($name)){
            $importer = Importer::where('name', $name);
            if(empty($id)) {
                $importer= $importer->first();
                if (!empty($importer)) {
                    $error = 'El nombre del importador ya existe';
                }
            }else{
                $importer = $importer->where('id','!=',$id)->first();
                if (!empty($importer)) {
                    $error = 'El nombre del importador ya existe para otro registro';
                }
            }
        }
        $data = [
            'success' => false,
            'message' => $error,
            'data' => $importer
        ];
        if(empty($error)){
            $importer = Importer::firstOrNew(['id' => $id]);
            $importer->fill($request->all());
            $importer->save();
            $data = [
                'success' => true,
                'message' => ($id)?'Importador editado con éxito':'Importador registrado con éxito',
                'data' => $importer
            ];
        }
        return $data;

    }

    public function destroy($id)
    {
        try {

            $importer = Importer::findOrFail($id);
            $importer->delete();

            return [
                'success' => true,
                'message' => 'Importador eliminada con éxito'
            ];

        } catch (\Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => "El importador esta siendo usado por otros registros, no puede eliminar"] : ['success' => false,'message' => "Error inesperado, no se pudo eliminar el importador"];

        }

    }




}
