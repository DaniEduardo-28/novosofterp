<?php

namespace Modules\Digemid\Http\Controllers;

use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Models\Tenant\CatIdentityDocumentTypes;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Digemid\Http\Requests\PatientsRequest;
use Modules\Digemid\Http\Resources\PatientsCollection;
use Modules\Digemid\Http\Resources\PatientsResource;
use Modules\Digemid\Models\Patients;

class PatientsController extends Controller
{

    public function index()
    {
        $api_service_token = \App\Models\Tenant\Configuration::getApiServiceToken();
        return view('digemid::patients.index', compact('api_service_token'));
    }

    public function columns()
    {
        return [
            'name' => 'Nombre',
            'number' => 'Documento'
        ];
    }

    public function tables()
    {
        $identity_document_types = IdentityDocumentType::whereActive()->get();
        $api_service_token = \App\Models\Tenant\Configuration::getApiServiceToken();
        $locations = func_get_locations();
        return compact('identity_document_types', 'api_service_token', 'locations');
    }

    public function records(Request $request)
    {
        $records = Patients::with('identity_document_type')
            ->when($request->column && $request->value, function ($query) use ($request) {
                $query->where($request->column, 'like', "%{$request->value}%");
            })
            ->orderBy('name', 'asc');

        return new PatientsCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function record($id)
    {
        $record = new PatientsResource(Patients::findOrFail($id));
        return $record;
    }

    /**
     * Crea o edita una nueva categoría.
     * El nombre de categoría debe ser único, por lo tanto se valida cuando el nombre existe.
     *
     * @param PatientsRequest $request
     *
     * @return array
     */
    public function store(PatientsRequest $request)
    {
        try {
            
            $id = (int)$request->input('id');

            $ubigeo = $request->input('ubigeo');
            $ubigeo_id = '010101';
            if (is_array($ubigeo) && count($ubigeo) === 3) {
                $ubigeo_id = $ubigeo[2];
            }

            $request->merge([
                'ubigeo' => $ubigeo_id,
                'identity_document_type_id' => $request->input('identity_document_type_id', 1),
                'number' => $request->input('number', '99999999'),
            ]);

            $patients = Patients::firstOrNew(['id' => $id]);
            $patients->fill($request->all());
            $patients->save();

            $data = [
                'success' => true,
                'message' => $id ? 'Paciente editado con éxito' : 'Paciente registrado con éxito',
                'data' => $patients
            ];

            return $data;
        } catch (\Throwable $th) {
            $data = [
                'success' => false,
                'message' => $th->getMessage(),
                'data' => null
            ];

            return $data;
        }
    }

    public function destroy($id)
    {
        try {

            $patients = Patients::findOrFail($id);
            $patients->delete();

            return [
                'success' => true,
                'message' => 'Paciente eliminado con éxito'
            ];
        } catch (\Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false, 'message' => "El paciente esta siendo usado por otros registros, no puede eliminar."] : ['success' => false, 'message' => "Error inesperado, no se pudo eliminar al paciente."];
        }
    }

    public function getDocumentoTypes()
    {
        $identity = IdentityDocumentType::select('id', 'description')->orderBy('description')->get();

        return [
            'success' => true,
            'data' => $identity,
        ];
    }
}
