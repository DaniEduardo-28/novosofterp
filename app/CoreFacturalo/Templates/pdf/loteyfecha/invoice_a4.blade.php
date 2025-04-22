@php
    use App\CoreFacturalo\Helpers\Template\TemplateHelper;
    $establishment = $document->establishment;
    $customer = $document->customer;
    $invoice = $document->invoice;
    $document_base = $document->note ? $document->note : null;

    //$path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
    $document_number = $document->series . '-' . str_pad($document->number, 8, '0', STR_PAD_LEFT);
    $accounts = \App\Models\Tenant\BankAccount::all();

    if ($document_base) {
        $affected_document_number = $document_base->affected_document
            ? $document_base->affected_document->series .
                '-' .
                str_pad($document_base->affected_document->number, 8, '0', STR_PAD_LEFT)
            : $document_base->data_affected_document->series .
                '-' .
                str_pad($document_base->data_affected_document->number, 8, '0', STR_PAD_LEFT);
    } else {
        $affected_document_number = null;
    }

    $payments = $document->payments;

    $document->load('reference_guides');

    $total_payment = $document->payments->sum('payment');
    $balance = $document->total - $total_payment - $document->payments->sum('change');

    //calculate items
    $allowed_items = 75 - \App\Models\Tenant\BankAccount::all()->count() * 3;
    $quantity_items = $document->items()->count();
    $cycle_items = $allowed_items - $quantity_items * 3;
    $total_weight = 0;

    // Condicion de pago
    $condition = TemplateHelper::getDocumentPaymentCondition($document);
    // Pago/Coutas detalladas
    $paymentDetailed = TemplateHelper::getDetailedPayment($document);

    $marca_agua = app_path(
        'CoreFacturalo' .
            DIRECTORY_SEPARATOR .
            'Templates' .
            DIRECTORY_SEPARATOR .
            'pdf' .
            DIRECTORY_SEPARATOR .
            'custom_gasolution' .
            DIRECTORY_SEPARATOR .
            'marca_agua.png',
    );

@endphp
<html>

<head>
    {{-- <title>{{ $document_number }}</title> --}}
    {{-- <link href="{{ $path_style }}" rel="stylesheet" /> --}}
</head>

<body>
    @if ($document->state_type->id == '11')
        <div class="company_logo_box" style="position: absolute; text-align: center; top:30%;">
            <img src="data:{{ mime_content_type(public_path('status_images' . DIRECTORY_SEPARATOR . 'anulado.png')) }};base64, {{ base64_encode(file_get_contents(public_path('status_images' . DIRECTORY_SEPARATOR . 'anulado.png'))) }}"
                alt="anulado" class="" style="opacity: 0.6;">
        </div>
    @endif
    <div class="item_watermark" style="position: absolute; text-align: center; top:35%;">
        <img style="width: 100%" height="180px"
            src="data:{{ mime_content_type(public_path("storage/uploads/logos/{$company->logo}")) }};base64, {{ base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}"))) }}"
            alt="{{ $company->name }}" class="" style="opacity: 0.2;width: 95%">
    </div>
    <table class="full-width">
        <tr>
            <td width="70%"></td>
            <td width="30%" class="text-center" style="font-size: 7px;">
                <strong>DESARROLLADO POR NEMCOR.COM.PE</strong>
            </td>
        </tr>
    </table>
    <table class="full-width">
        <tr>
            @if ($company->logo)
                <td width="20%">
                    <div class="company_logo_box">
                        <img src="data:{{ mime_content_type(public_path("storage/uploads/logos/{$company->logo}")) }};base64, {{ base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}"))) }}"
                            alt="{{ $company->name }}" class="company_logo" style="max-width: 150px;">
                    </div>
                </td>
            @else
                <td width="20%">
                    {{-- <img src="{{ asset('logo/logo.jpg') }}" class="company_logo" style="max-width: 150px"> --}}
                </td>
            @endif
            <td width="50%" class="pl-3">
                <div class="text-left">
                    <h4 class="">{{ $company->name }}</h4>
                    <h5>{{ 'RUC ' . $company->number }}</h5>
                    <h6 style="text-transform: uppercase;">
                        {{ $establishment->address !== '-' ? $establishment->address : '' }}
                        {{ $establishment->district_id !== '-' ? ', ' . $establishment->district->description : '' }}
                        {{ $establishment->province_id !== '-' ? ', ' . $establishment->province->description : '' }}
                        {{ $establishment->department_id !== '-' ? '- ' . $establishment->department->description : '' }}
                    </h6>

                    @isset($establishment->trade_address)
                        <h6>{{ $establishment->trade_address !== '-' ? $establishment->trade_address : '' }}
                        </h6>
                    @endisset

                    <h6>{{ $establishment->telephone !== '-' ? 'Central telefónica: ' . $establishment->telephone : '' }}
                    </h6>

                    <h6>{{ $establishment->email !== '-' ? 'Email: ' . $establishment->email : '' }}</h6>

                    @isset($establishment->web_address)
                        <h6>{{ $establishment->web_address !== '-' ? 'Web: ' . $establishment->web_address : '' }}</h6>
                    @endisset

                    @isset($establishment->aditional_information)
                        <h6>{{ $establishment->aditional_information !== '-' ? $establishment->aditional_information : '' }}
                        </h6>
                    @endisset
                </div>
            </td>
            <td width="30%" class="border-box py-2 px-2 text-center">
                <h5 class="font-bold">{{ 'R.U.C. ' . $company->number }}</h5>
                <h5 class="text-center font-bold">{{ $document->document_type->description }}</h5>
                <br>
                <h5 class="text-center font-bold">{{ $document_number }}</h5>
            </td>
        </tr>
    </table>
    <table class="full-width mt-1">
        <tr>
            <td width="57%" class="border-box pl-3">
                <table class="full-width">
                    <tr>
                        <td class="font-sm" width="80px">
                            <strong>Razón Social</strong>
                        </td>
                        <td class="font-sm" width="8px">:</td>
                        <td class="font-sm">
                            {{ $customer->name }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-sm" width="80px">
                            <strong>{{ $customer->identity_document_type->description }}</strong>
                        </td>
                        <td class="font-sm" width="8px">:</td>
                        <td class="font-sm">
                            {{ $customer->number }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-sm" width="80px">
                            <strong>Dirección</strong>
                        </td>
                        <td class="font-sm" width="8px">:</td>
                        <td class="font-sm">
                            @if ($customer->address !== '')
                                <span style="text-transform: uppercase;">
                                    {{ $customer->address }}
                                    {{ $customer->district_id !== '-' ? ', ' . $customer->district->description : '' }}
                                    {{ $customer->province_id !== '-' ? ', ' . $customer->province->description : '' }}
                                    {{ $customer->department_id !== '-' ? '- ' . $customer->department->description : '' }}
                                </span>
                            @endif
                        </td>
                    </tr>

                    @if (!is_null($document_base))
                        <tr>
                            <td class="font-sm font-bold" width="80px">Doc. Afectado</td>
                            <td class="font-sm" width="8px">:</td>
                            <td class="font-sm">{{ $affected_document_number }}</td>
                        </tr>
                        <tr>
                            <td class="font-sm font-bold" width="80px">Tipo de nota</td>
                            <td class="font-sm">:</td>
                            <td class="font-sm">
                                {{ $document_base->note_type === 'credit' ? $document_base->note_credit_type->description : $document_base->note_debit_type->description }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-sm font-bold" width="80px">Descripción</td>
                            <td class="font-sm">:</td>
                            <td class="font-sm">{{ $document_base->note_description }}</td>
                        </tr>
                    @endif
                </table>
            </td>
            <td width="3%"></td>
            <td width="40%" class="border-box pl-2">
                <table class="full-width">
                    <tr>
                        <td class="font-sm" width="70px">
                            <strong>FECHA EMIS.</strong>
                        </td>
                        <td class="font-sm" width="8px">:</td>
                        <td class="font-sm" width="70px">
                            {{ $document->date_of_issue->format('d/m/Y') }}
                        </td>
                        <td class="font-sm" width="40px">
                            <strong>N° GUIA</strong>
                        </td>
                        <td class="font-sm" width="8px">:</td>
                        <td class="font-sm">
                            @if ($document->guides)
                                @foreach ($document->guides as $item)
                                    {{ $item->number }},
                                @endforeach
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td class="font-sm" width="70px">
                            <strong>FECHA VCTO.</strong>
                        </td>
                        <td class="font-sm" width="8px">:</td>
                        <td class="font-sm">
                            {{ $invoice->date_of_due->format('d/m/Y') }}
                        </td>
                        <td class="font-sm" width="40px">
                            <strong>N° O/C</strong>
                        </td>
                        <td class="font-sm" width="8px">:</td>
                        <td class="font-sm">
                            {{ $document->purchase_order }}
                        </td>
                    </tr>

                    <tr>
                        <td class="font-sm" width="70px">
                            <strong>F. PAGO</strong>
                        </td>
                        <td class="font-sm" width="8px">:</td>
                        <td class="font-sm">
                            {{ $condition }}
                        </td>
                        <td class="font-sm" width="40px">
                            <strong>N° COT.</strong>
                        </td>
                        <td class="font-sm" width="8px">:</td>
                        <td class="font-sm">
                            @if ($document->quotation_id)
                                {{ $document->quotation->identifier }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td class="font-sm">
                            <strong>MONEDA</strong>
                        </td>
                        <td class="font-sm" width="8px">:</td>
                        <td class="font-sm" colspan="4">
                            {{ strtoupper($document->currency_type->description) }}
                        </td>
                    </tr>

                </table>
            </td>
            {{-- <td width="5%" class="p-0 m-0">
            <img src="data:image/png;base64, {{ $document->qr }}" class="p-0 m-0" style="width: 120px;" />
        </td> --}}
        </tr>
    </table>

    <table class="full-width mt-1 mb-0">
        <thead>
            <tr class="bg-blue">
                <th class="border-top-bottom text-center py-1 desc" class="cell-solid text-white" width="8%">ITEM
                </th>
                <th class="border-top-bottom text-center py-1 desc" class="cell-solid text-white" width="12%">
                    CÓDIGO</th>
                <th class="border-top-bottom text-center py-1 desc" class="cell-solid text-white" width="6%">
                    CANT.</th>
                <th class="border-top-bottom text-center py-1 desc" class="cell-solid text-white" width="5%">U.M.
                </th>
                <th class="border-top-bottom text-center py-1 desc" class="cell-solid text-white">
                    DESCRIPCIÓN
                </th>
                <th class="border-top-bottom text-right py-1 desc" class="cell-solid text-white" width="12%">
                    PRECIO</th>
                <th class="border-top-bottom text-center py-1 desc" class="cell-solid text-white" width="12%">
                    TOTAL</th>
            </tr>
        </thead>
        <tbody class="">
            @php
                $index = 0;
            @endphp
            @foreach ($document->items as $row)
                @php
                    $index += 1;
                @endphp
                <tr>
                    <td class="p-1 text-center align-top desc cell-solid-rl">
                        @if ($index < 10)
                            0{{ $index }}
                        @else
                            {{ $index }}
                        @endif
                    </td>
                    <td class="p-1 text-center align-top desc cell-solid-rl">{{ $row->item->internal_id }}</td>
                    <td class="p-1 text-center align-top desc cell-solid-rl">
                        @if ((int) $row->quantity != $row->quantity)
                            {{ $row->quantity }}
                        @else
                            {{ number_format($row->quantity, 0) }}
                        @endif
                    </td>
                    <td class="p-1 text-center align-top desc cell-solid-rl">{{ $row->item->unit_type_id }}</td>
                    <td class="p-1 text-left align-top desc text-upp cell-solid-rl">
                        @if ($row->name_product_pdf)
                            {!! $row->name_product_pdf !!}
                        @else
                            {!! $row->item->description !!}
                        @endif

                        @if (!empty($row->item->presentation))
                            {!! $row->item->presentation->description !!}
                        @endif

                        @if ($row->attributes)
                            @foreach ($row->attributes as $attr)
                                @if ($attr->attribute_type_id === '5032')
                                    @php
                                        $total_weight += $attr->value * $row->quantity;
                                    @endphp
                                @endif
                                <br /><span style="font-size: 9px">{!! $attr->description !!} :
                                    {{ $attr->value }}</span>
                            @endforeach
                        @endif

                        @if ($row->item->is_set == 1)
                            <br>
                            @inject('itemSet', 'App\Services\ItemSetService')
                            {{ join('-', $itemSet->getItemsSet($row->item_id)) }}
                        @endif
                    </td>
                    <td class="p-1 text-right align-top desc cell-solid-rl">{{ number_format($row->unit_price, 2) }}
                    </td>
                    <td class="p-1 text-right align-top desc cell-solid-rl">{{ number_format($row->total, 2) }}</td>
                </tr>
            @endforeach

            @for ($i = 0; $i < $cycle_items; $i++)
                <tr>
                    <td class="p-1 text-center align-top desc cell-solid-rl"></td>
                    <td class="p-1 text-center align-top desc cell-solid-rl">
                    </td>
                    <td class="p-1 text-center align-top desc cell-solid-rl"></td>
                    <td class="p-1 text-left align-top desc text-upp cell-solid-rl">
                    </td>
                    <td class="p-1 text-right align-top desc cell-solid-rl"></td>
                    <td class="p-1 text-right align-top desc cell-solid-rl">
                    </td>
                    <td class="p-1 text-right align-top desc cell-solid-rl"></td>
                </tr>
            @endfor

            <tr>
                <td class="p-1 text-left align-top desc cell-solid font-bold" colspan="7" style="font-size: 9px;">
                    SON:
                    @foreach (array_reverse((array) $document->legends) as $row)
                        @if ($row->code == '1000')
                            {{ strtoupper($row->value) }} {{ strtoupper($document->currency_type->description) }}
                            (S.E.U.O)
                            {{-- @else
                            {{ $row->code }}: {{ $row->value }} --}}
                        @endif
                    @endforeach
                </td>
            </tr>

        </tbody>
    </table>
    <table class="full-width mt-0 mb-0">
        <tbody>
            <tr>
                <td class="p-1 text-left align-top desc cell-solid-rl"><strong>
                        VENDEDOR</strong></td>
                <td class="p-1 text-left align-top desc cell-solid-rl"><strong>
                        ELABORADO POR</strong></td>
                <td class="p-1 text-center align-top desc cell-solid-rl"><strong>
                        SUB TOTAL</strong></td>
                <td class="p-1 text-center align-top desc cell-solid-rl"><strong>
                        IGV(18.00%)</strong></td>
                <td class="p-1 text-center align-top desc cell-solid-rl"><strong>
                        IMPORTE TOTAL</strong></td>
            </tr>
            <tr>
                <td width="" class="p-1 text-left align-top desc cell-solid">{{ $document->seller->name }}</td>
                <td width="" class="p-1 text-left align-top desc cell-solid">{{ $document->user->name }}</td>
                <td width="15%" class="p-1 text-right align-top desc cell-solid">
                    {{ $document->currency_type->symbol }}
                    {{ number_format($document->total - $document->total_igv, 2) }}</td>
                <td width="15%" class="p-1 text-right align-top desc cell-solid">
                    {{ $document->currency_type->symbol }}
                    {{ number_format($document->total_igv, 2) }}</td>
                <td width="15%" class="p-1 text-right align-top desc cell-solid">
                    {{ $document->currency_type->symbol }}
                    {{ number_format($document->total, 2) }}</td>
            </tr>
        </tbody>
    </table>
    @if ($document->detraction)
        <table class="full-width mt-0 mb-0">
            <tbody>
                <tr>
                    <td class="text-center align-top desc" style="font-size: 8px;">
                        - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
                        - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
                        - -
                        - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
                        <br>
                        <strong>
                            Comprobante Afecto a Detracción del {{ $document->detraction->percentage }} % - Importe de
                            Detracción : S/ {{ $document->detraction->amount }} - No Cta. BN
                            ({{ $document->detraction->bank_account }})
                        </strong>
                        <br>
                        - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
                        - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
                        - -
                        - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
                    </td>
                </tr>
            </tbody>
        </table>
    @endif
    <table class="full-width p-0 m-0">
        <tbody>
            <tr>
                <td class="p-1 text-left align-top desc cell-solid-rl" colspan="3" rowspan="5">
                    @php
                        $total_packages = $document->items()->sum('quantity');
                    @endphp

                    {{-- Detalles de pago --}}
                    @if (!empty($paymentDetailed))
                        @foreach ($paymentDetailed as $detailed)
                            <strong> {{ isset($paymentDetailed['PAGOS']) ? 'Pagos:' : 'Cuotas:' }}</strong>
                            <br>
                            @foreach ($detailed as $row)
                                {{ $row['description'] }} -
                                {{ $row['reference'] }}
                                {{ $row['symbol'] }}
                                {{ $row['amount'] }}
                                <br>
                            @endforeach
                        @endforeach
                        <br>
                    @endif
                    {{-- <strong> Total bultos:</strong>
                    @if ((int) $total_packages != $total_packages)
                        {{ $total_packages }}
                    @else
                        {{ number_format($total_packages, 0) }}
                    @endif
                    <br>

                    <strong> Total Peso:</strong>
                    {{ $total_weight }} KG
                    <br>

                    <strong> Observación:</strong>
                    @foreach ($document->additional_information as $information)
                        @if ($information)
                            {{ $information }} <br>
                        @endif
                    @endforeach --}}

                    <br>
                </td>
                <td class="p-1 text-center align-top desc cell-solid-rl" rowspan="5">

                    <img src="data:image/png;base64, {{ $document->qr }}" class="p-0 m-0"
                        style="width: 120px;" /><br>
                    Código Hash: {{ $document->hash }}

                </td>
            </tr>
        </tbody>
    </table>
    @if ($document != null)

        <table class="full-width border-box my-2">
            <tr>
                <th class="p-1" width="25%">Banco</th>
                <th class="p-1">Moneda</th>
                <th class="p-1" width="30%">Código de Cuenta Interbancaria</th>
                <th class="p-1" width="25%">Código de Cuenta</th>
            </tr>
            @foreach ($accounts as $account)
                <tr>
                    <td class="text-center">{{ $account->bank->description }}</td>
                    <td class="text-center text-upp">{{ $account->currency_type->description }}</td>
                    <td class="text-center">
                        @if ($account->cci)
                            {{ $account->cci }}
                        @endif
                    </td>
                    <td class="text-center">{{ $account->number }}</td>
                </tr>
            @endforeach
        </table>
    @endif
    @if ($document->terms_condition)
        <br>
        <table class="full-width">
            <tr>
                <td>
                    <h6 style="font-size: 12px; font-weight: bold;">Términos y condiciones del servicio</h6>
                    {!! $document->terms_condition !!}
                </td>
            </tr>
        </table>
    @endif
    {{-- <div class="text-center">
        <img style="width: 45%" height="80px"
            src="data:{{ mime_content_type(public_path("storage/uploads/logos/{$company->logo}")) }};base64, {{ base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}"))) }}"
            alt="image" class="">
    </div> --}}
</body>

</html>
