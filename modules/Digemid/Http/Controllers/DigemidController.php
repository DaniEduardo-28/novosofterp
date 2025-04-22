<?php

namespace Modules\Digemid\Http\Controllers;

use App\Models\Tenant\Cash;
use App\Models\Tenant\Company;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\BusinessTurn\Models\BusinessTurn;

class DigemidController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('digemid::index');
    }

    public function products()
    {
        return view('digemid::products');
    }

    public function caja()
    {
        return view('digemid::caja');
    }

    public function pos()
    {
        $cash = Cash::where([['user_id', auth()->user()->id],['state', true]])->first();

        if(!$cash) return redirect()->route('tenant.cash.index');

        $configuration = Configuration::first();

        $company = Company::select('soap_type_id')->first();
        $soap_company  = $company->soap_type_id;
        $business_turns = BusinessTurn::select('active')->where('id', 4)->first();

        return view('digemid::pos', compact('configuration', 'soap_company', 'business_turns'));
    }

    public function updateExportableItem(Item $item){

        $catDigemid = $item->cat_digemid()->first();
        if(!empty($catDigemid)) {
            $catDigemid->toggleActive()->push();
        }
        $configuration = Configuration::first();
        return $item->getCollectionData($configuration);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('digemid::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('digemid::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('digemid::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
