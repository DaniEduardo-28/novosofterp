<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Report\Exports\CustomerExport;
use Illuminate\Http\Request;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Document;
use App\Models\Tenant\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Report\Http\Resources\DocumentCollection;
use Modules\Report\Traits\ReportTrait;


class ReportCustomerController extends Controller
{
    use ReportTrait;

    public function filter()
    {

        $document_types = [];

        $persons = $this->getPersons('customers');

        $establishments = Establishment::all()->transform(function ($row) {
            return [
                'id' => $row->id,
                'name' => $row->description
            ];
        });

        return compact('document_types', 'establishments', 'persons');
    }


    public function index()
    {

        return view('report::customers.index');
    }

    public function records(Request $request)
    {
        $records = $this->getRecordsCustomers($request->all(), Document::class);
        return new DocumentCollection($records->paginate(config('tenant.items_per_page')));
    }



    public function getRecordsCustomers($request, $model)
    {

        // dd($request['period']);
        $document_type_id = $request['document_type_id'];
        $establishment_id = $request['establishment_id'];
        $period = $request['period'];
        $date_start = $request['date_start'];
        $date_end = $request['date_end'];
        $month_start = $request['month_start'];
        $month_end = $request['month_end'];
        $person_id = $request['person_id'];
        $type_person = $request['type_person'];

        $d_start = null;
        $d_end = null;
        /** @todo: Eliminar periodo, fechas y cambiar por

        $date_start = $request['date_start'];
        $date_end = $request['date_end'];
        \App\CoreFacturalo\Helpers\Functions\FunctionsHelper\FunctionsHelper::setDateInPeriod($request, $date_start, $date_end);
         */
        switch ($period) {
            case 'month':
                $d_start = Carbon::parse($month_start . '-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_start . '-01')->endOfMonth()->format('Y-m-d');
                // $d_end = Carbon::parse($month_end.'-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'between_months':
                $d_start = Carbon::parse($month_start . '-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_end . '-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'date':
                $d_start = $date_start;
                $d_end = $date_start;
                // $d_end = $date_end;
                break;
            case 'between_dates':
                $d_start = $date_start;
                $d_end = $date_end;
                break;
        }

        $records = $this->dataCustomers($document_type_id, $establishment_id, $d_start, $d_end, $person_id, $type_person, $model);

        return $records;
    }


    private function dataCustomers($document_type_id, $establishment_id, $date_start, $date_end, $person_id, $type_person, $model)
    {

        $document_payments = DB::table('document_payments')
            ->select('document_id', DB::raw('SUM(payment) as total_payment'))
            ->groupBy('document_id');

        $data = $model::leftJoinSub($document_payments, 'payments', function ($join) {
            $join->on('documents.id', '=', 'payments.document_id');
        })
            ->whereBetween('documents.date_of_issue', [$date_start, $date_end])
            ->where('documents.customer_id', $person_id)
            ->whereIn('documents.document_type_id', ['01', '03', '07'])
            ->whereIn('documents.state_type_id', ['01', '03', '05', '07', '13'])
            ->select(DB::raw('documents.*, payments.total_payment'))
            ->latest();

        return $data;
    }

    public function excel(Request $request)
    {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;

        $records = $this->getRecordsCustomers($request->all(), Document::class)->get();

        return (new CustomerExport)
            ->records($records)
            ->company($company)
            ->establishment($establishment)
            ->download('Reporte_Ventas_por_Cliente_' . Carbon::now() . '.xlsx');
    }

    public function pdf(Request $request)
    {
        ini_set('memory_limit', '4026M');
        ini_set("pcre.backtrack_limit", "5000000");

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;

        $records = $this->getRecordsCustomers($request->all(), Document::class)->get();

        $pdf = PDF::loadView('report::customers.report_pdf', compact("records", "company", "establishment"))->setPaper('a4', 'portrait');

        $filename = 'Reporte_Ventas_por_Cliente_' . Carbon::now();

        return $pdf->download($filename . '.pdf');
    }
}
