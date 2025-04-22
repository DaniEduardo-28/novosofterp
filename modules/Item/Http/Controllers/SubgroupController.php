<?php

namespace Modules\Item\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Item\Http\Requests\SubgroupRequest;
use Modules\Item\Models\Subgroup;
use Modules\Item\Models\Category;
use Modules\Item\Http\Resources\SubgroupCollection;
use Modules\Item\Http\Resources\sub;
use Modules\Item\Http\Resources\SubgroupResource;

class SubgroupController extends Controller
{

    public function index()
    {
        return view('item::subgroup.index');
    }

    public function columns()
    {
        return [
            'name' => 'Nombre'
        ];
    }

    public function tables()
    {
        $categories = Category::all();

        return compact(
            'categories'
        );
    }

    public function records(Request $request)
    {
        $records = Subgroup::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new SubgroupCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function record($id)
    {
        $record = new SubgroupResource(Subgroup::findOrFail($id));
        return $record;
    }

    /**
     * Crea o edita una nueva categoría.
     * El nombre de categoría debe ser único, por lo tanto se valida cuando el nombre existe.
     *
     * @param SubgroupRequest $request
     *
     * @return array
     */
    public function store(SubgroupRequest $request)
    {
        $id = (int)$request->input('id');
        $name = $request->input('name');
        $categoryId = $request->input('caategory_id');
        $error = null;
        
        $subgroup = Subgroup::where('name', $name)
            ->where('category_id', $categoryId);

        if ($id) {
            $subgroup = $subgroup->where('id', '!=', $id);
        }

        if ($subgroup->exists()) {
            $error = 'El nombre del subgrupo ya existe en esta categoría.';
        }

        $data = [
            'success' => false,
            'message' => $error,
            'data' => null
        ];

        if (!$error) {
            $subgroup = Subgroup::firstOrNew(['id' => $id]);
            $subgroup->fill($request->all());
            $subgroup->save();

            $data = [
                'success' => true,
                'message' => $id ? 'Subgrupo editado con éxito' : 'Subgrupo registrado con éxito',
                'data' => $subgroup
            ];
        }

        return $data;

    }

    public function destroy($id)
    {
        try {

            $subgroup = Subgroup::findOrFail($id);
            $subgroup->delete();

            return [
                'success' => true,
                'message' => 'Grupo eliminado con éxito'
            ];

        } catch (\Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => "El grupo esta siendo usado por otros registros, no puede eliminar."] : ['success' => false,'message' => "Error inesperado, no se pudo eliminar el grupo."];

        }

    }

    public function getCategories()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();

        return [
            'success' => true,
            'data' => $categories,
        ];
    }


}
