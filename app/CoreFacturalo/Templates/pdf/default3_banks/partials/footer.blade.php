@php
    if ($document != null) {
        $establishment = $document->establishment;
        $customer = $document->customer;
        $invoice = $document->invoice;
        $document_base = ($document->note) ? $document->note : null;

        //$path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
        $document_number = $document->series.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT);

        if($document_base) {

            $affected_document_number = ($document_base->affected_document) ? $document_base->affected_document->series.'-'.str_pad($document_base->affected_document->number, 8, '0', STR_PAD_LEFT) : $document_base->data_affected_document->series.'-'.str_pad($document_base->data_affected_document->number, 8, '0', STR_PAD_LEFT);

        } else {

            $affected_document_number = null;
        }

        $payments = $document->payments;

        // $document->load('reference_guides');

        if ($document->payments) {
            $total_payment = $document->payments->sum('payment');
            $balance = ($document->total - $total_payment) - $document->payments->sum('change');
        }


    }

    $accounts = \App\Models\Tenant\BankAccount::all();

    $path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
@endphp
<head>
    <link href="{{ $path_style }}" rel="stylesheet" />
</head>
<body>
    @if ($document)
    @if (class_basename($document) === 'Document')
        @switch($document->document_type_id)
            @case('03')
                <table class="full-width">
                    <tr>
                        <td class="text-center desc font-bold">Representación impresa de la BOLETA DE VENTA ELECTRÓNICA, para
                            consultar el comprobante ingresar a {!! url('/buscar') !!}</td>
                    </tr>
                </table>
            @break

            @case('01')
                <table class="full-width">
                    <tr>
                        <td class="text-center desc font-bold">Representación impresa de la FACTURA ELECTRÓNICA, para
                            consultar el comprobante ingresar a {!! url('/buscar') !!}</td>
                    </tr>
                </table>
            @break

            @case('07')
                <table class="full-width">
                    <tr>
                        <td class="text-center desc font-bold">Representación impresa de la NOTA DE CRÉDITO ELECTRÓNICA, para
                            consultar el comprobante ingresar a {!! url('/buscar') !!}</td>
                    </tr>
                </table>
            @break

            @case('08')
                <table class="full-width">
                    <tr>
                        <td class="text-center desc font-bold">Representación impresa de la NOTA DE DÉBITO ELECTRÓNICA, para
                            consultar el comprobante ingresar a {!! url('/buscar') !!}</td>
                    </tr>
                </table>
            @break

            @default
        @endswitch
    @endif
@endif
</body>
