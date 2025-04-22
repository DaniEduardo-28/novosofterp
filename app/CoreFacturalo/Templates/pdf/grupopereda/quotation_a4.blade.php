@php
    $establishment = $document->establishment;
    $customer = $document->customer;
    $accounts = \App\Models\Tenant\BankAccount::all();
    $tittle = $document->prefix . '-' . str_pad($document->id, 8, '0', STR_PAD_LEFT);
    $logo = "storage/uploads/logos/{$company->logo}";
    if ($establishment->logo) {
        $logo = "{$establishment->logo}";
    }
    $firma = app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . 'grupopereda' . DIRECTORY_SEPARATOR . 'firma.png');
@endphp
<html>

<head>
    {{-- <title>{{ $tittle }}</title> --}}
    {{-- <link href="{{ $path_style }}" rel="stylesheet" /> --}}
</head>

<body>

    <table class="full-width">
        <tr>
            <td width="3%"></td>
            @if ($company->logo)
                <td width="47%">
                    <div class="company_logo_box">
                        <img src="data:{{ mime_content_type(public_path("storage/uploads/logos/{$company->logo}")) }};base64, {{ base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}"))) }}"
                            alt="{{ $company->name }}" class="company_logo" style="max-width: 200px;">
                    </div>
                </td>
            @else
                <td width="47%">
                    {{-- <img src="{{ asset('logo/logo.jpg') }}" class="company_logo" style="max-width: 150px"> --}}
                </td>
            @endif
            <td width="20%">
                {{-- <img src="{{ asset('logo/logo.jpg') }}" class="company_logo" style="max-width: 150px"> --}}
            </td>
            <td width="30%" class="pl-3 text-center">
                <div class="text-left">
                    <h5 class="">{{ $company->name }}</h5>
                    <h5>{{ 'RUC: ' . $company->number }}</h5>
                    <h5>{{ $establishment->telephone !== ' ' ? $establishment->telephone : '' }}</h5>
                    <h5>{{ $establishment->email !== ' ' ? $establishment->email : '' }}</h5>
                    {{-- <h6 style="text-transform: uppercase;">
                        {{ $establishment->address !== '-' ? $establishment->address : '' }}
                        {{ $establishment->district_id !== '-' ? ', ' . $establishment->district->description : '' }}
                        {{ $establishment->province_id !== '-' ? ', ' . $establishment->province->description : '' }}
                        {{ $establishment->department_id !== '-' ? '- ' . $establishment->department->description : '' }}
                    </h6>

                    @isset($establishment->trade_address)
                        <h6>{{ $establishment->trade_address !== '-' ? 'D. Comercial: ' . $establishment->trade_address : '' }}
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
                    @endisset --}}
                </div>
            </td>
            {{-- <td width="30%" class="border-box py-4 px-2 text-center">
                <h5 class="text-center">COTIZACIÓN</h5>
                <h3 class="text-center">{{ $tittle }}</h3>
            </td> --}}
        </tr>
    </table>
    <table class="full-width mt-3">
        <tr>
            <td width="15%">Fecha:</td>
            <td width="55%" colspan="2">{{ $document->date_of_issue->format('d/m/Y') }}</td>
            <td width="30%" rowspan="3" class="border-box text-center">
                <h5 class="text-center">COTIZACIÓN</h5>
                <h6 class="text-center">{{ $tittle }}</h6>
            </td>
            {{-- <td width="15%">Cliente:</td>
            <td width="45%">{{ $customer->name }}</td> --}}
        </tr>
        <tr>
            <td width="15%">Cliente:</td>
            <td width="55%" colspan="2">{{ $customer->name }}</td>
        </tr>
        <tr>
            <td width="15%">Atención:</td>
            <td width="55%" colspan="2">{{ $document->contact }}</td>
        </tr>
        <tr>
            <td width="15%">{{ $customer->identity_document_type->description }}:</td>
            <td width="55%" colspan="3">{{ $customer->number }}</td>
        </tr>
        <tr>
            <td width="15%">Dirección:</td>
            <td width="85%" colspan="3">
                {{ $customer->address }}
                {{ $customer->district_id !== '-' ? ', ' . $customer->district->description : '' }}
                {{ $customer->province_id !== '-' ? ', ' . $customer->province->description : '' }}
                {{ $customer->department_id !== '-' ? '- ' . $customer->department->description : '' }}
            </td>
        </tr>
        <tr>
            <td width="15%">Forma de pago:</td>
            <td width="35%">{{ $document->payment_method_type->description }}</td>
            <td width="20%">Tiempo de entrega:</td>
            <td width="30%">{{ $document->delivery_date }}</td>
        </tr>
        <tr>
            <td width="15%">Vendedor:</td>
            <td width="35%">
                @if ($document->seller->name)
                    {{ $document->seller->name }}
                @else
                    {{ $document->user->name }}
                @endif
            </td>
            <td width="20%">Validez de la oferta:</td>
            <td width="30%">{{ $document->date_of_due }}</td>
        </tr>
        {{-- <tr>
            <td>{{ $customer->identity_document_type->description }}:</td>
            <td>{{ $customer->number }}</td>
            @if ($document->date_of_due)
                <td width="25%">Tiempo de Validez:</td>
                <td width="15%">{{ $document->date_of_due }}</td>
            @endif
        </tr>
        @if ($customer->address !== '')
            <tr>
                <td class="align-top">Dirección:</td>
                <td colspan="">
                    {{ $customer->address }}
                    {{ $customer->district_id !== '-' ? ', ' . $customer->district->description : '' }}
                    {{ $customer->province_id !== '-' ? ', ' . $customer->province->description : '' }}
                    {{ $customer->department_id !== '-' ? '- ' . $customer->department->description : '' }}
                </td>
                @if ($document->delivery_date)
                    <td width="25%">Tiempo de Entrega:</td>
                    <td width="15%">{{ $document->delivery_date }}</td>
                @endif
            </tr>
        @endif
        @if ($document->payment_method_type)
            <tr>
                <td class="align-top">T. Pago:</td>
                <td colspan="">
                    {{ $document->payment_method_type->description }}
                </td>
                @if ($document->sale_opportunity)
                    <td width="25%">O. Venta:</td>
                    <td width="15%">{{ $document->sale_opportunity->number_full }}</td>
                @endif
            </tr>
        @endif
        @if ($document->account_number)
            <tr>
                <td class="align-top">N° Cuenta:</td>
                <td colspan="3">
                    {{ $document->account_number }}
                </td>
            </tr>
        @endif
        @if ($document->shipping_address)
            <tr>
                <td class="align-top">Dir. Envío:</td>
                <td colspan="3">
                    {{ $document->shipping_address }}
                </td>
            </tr>
        @endif
        @if ($customer->telephone)
            <tr>
                <td class="align-top">Teléfono:</td>
                <td colspan="3">
                    {{ $customer->telephone }}
                </td>
            </tr>
        @endif
        <tr>
            <td class="align-top">Vendedor:</td>
            <td colspan="3">
                @if ($document->seller->name)
                    {{ $document->seller->name }}
                @else
                    {{ $document->user->name }}
                @endif
            </td>
        </tr>
        @if ($document->contact)
            <tr>
                <td class="align-top">Contacto:</td>
                <td colspan="3">
                    {{ $document->contact }}
                </td>
            </tr>
        @endif
        @if ($document->phone)
            <tr>
                <td class="align-top">Telf. Contacto:</td>
                <td colspan="3">
                    {{ $document->phone }}
                </td>
            </tr>
        @endif --}}
    </table>

    <table class="full-width mt-10 mb-10">
        <thead>
            <tr style="background-color: rgba(38, 84, 192, 0.411)0, 0.435);">
                <th class="border-top-bottom text-center py-2" style="color: #fff;" width="8%">CANT.</th>
                <th class="border-top-bottom text-center py-2" style="color: #fff;" width="8%">UNIDAD</th>
                <th class="border-top-bottom text-left py-2" style="color: #fff;">DESCRIPCIÓN</th>
                <th class="border-top-bottom text-center py-2" style="color: #fff;" width="12%">P.UNIT</th>
                <th class="border-top-bottom text-center py-2" style="color: #fff;" width="8%">DTO.</th>
                <th class="border-top-bottom text-center py-2" style="color: #fff;" width="12%">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($document->items as $row)
                @php
                    $brand = \App\CoreFacturalo\Helpers\Template\TemplateHelper::getBrandFormItem($row);

                @endphp
                <tr>
                    <td class="text-center align-top">
                        @if ((int) $row->quantity != $row->quantity)
                            {{ $row->quantity }}
                        @else
                            {{ number_format($row->quantity, 0) }}
                        @endif
                    </td>
                    <td class="text-center align-top">{{ $row->item->unit_type_id }}</td>
                    <td class="text-left">
                        @if ($row->item->name_product_pdf ?? false)
                            {!! $row->item->name_product_pdf ?? '' !!}
                        @else
                            {!! $row->item->description !!}
                        @endif
                        @if (!empty($row->item->presentation))
                            {!! $row->item->presentation->description !!}
                        @endif
                        @if ($row->attributes)
                            @foreach ($row->attributes as $attr)
                                <br /><span style="font-size: 9px">{!! $attr->description !!} : {{ $attr->value }}</span>
                            @endforeach
                        @endif
                        @if ($row->discounts)
                            @foreach ($row->discounts as $dtos)
                                <br /><span style="font-size: 9px">{{ $dtos->factor * 100 }}%
                                    {{ $dtos->description }}</span>
                            @endforeach
                        @endif

                        @if ($row->item !== null && property_exists($row->item, 'extra_attr_value') && $row->item->extra_attr_value != '')
                            <br /><span style="font-size: 9px">{{ $row->item->extra_attr_name }}:
                                {{ $row->item->extra_attr_value }}</span>
                        @endif

                        @if ($row->item->is_set == 1)
                            <br>
                            @inject('itemSet', 'App\Services\ItemSetService')
                            @foreach ($itemSet->getItemsSet($row->item_id) as $item)
                                {{ $item }}<br>
                            @endforeach
                        @endif

                    </td>
                    <td class="text-right align-top">{{ number_format($row->unit_price, 2) }}</td>
                    <td class="text-right align-top">
                        @if ($row->discounts)
                            @php
                                $total_discount_line = 0;
                                foreach ($row->discounts as $disto) {
                                    $total_discount_line = $total_discount_line + $disto->amount;
                                }
                            @endphp
                            {{ number_format($total_discount_line, 2) }}
                        @else
                            0
                        @endif
                    </td>
                    <td class="text-right align-top">{{ number_format($row->total, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="6" class="border-bottom"></td>
                </tr>
            @endforeach
            @if ($document->total_exportation > 0)
                <tr>
                    <td colspan="5" class="text-right font-bold">OP. EXPORTACIÓN:
                        {{ $document->currency_type->symbol }}</td>
                    <td class="text-right font-bold">{{ number_format($document->total_exportation, 2) }}</td>
                </tr>
            @endif
            @if ($document->total_free > 0)
                <tr>
                    <td colspan="5" class="text-right font-bold">OP. GRATUITAS:
                        {{ $document->currency_type->symbol }}</td>
                    <td class="text-right font-bold">{{ number_format($document->total_free, 2) }}</td>
                </tr>
            @endif
            @if ($document->total_unaffected > 0)
                <tr>
                    <td colspan="5" class="text-right font-bold">OP. INAFECTAS:
                        {{ $document->currency_type->symbol }}</td>
                    <td class="text-right font-bold">{{ number_format($document->total_unaffected, 2) }}</td>
                </tr>
            @endif
            @if ($document->total_exonerated > 0)
                <tr>
                    <td colspan="5" class="text-right font-bold">OP. EXONERADAS:
                        {{ $document->currency_type->symbol }}</td>
                    <td class="text-right font-bold">{{ number_format($document->total_exonerated, 2) }}</td>
                </tr>
            @endif
            @if ($document->total_taxed > 0)
                <tr>
                    <td colspan="5" class="text-right font-bold">SUBTOTAL:
                        {{ $document->currency_type->symbol }}</td>
                    <td class="text-right font-bold">{{ number_format($document->total_taxed, 2) }}</td>
                </tr>
            @endif
            @if ($document->total_discount > 0)
                <tr>
                    <td colspan="5" class="text-right font-bold">
                        {{ $document->total_prepayment > 0 ? 'ANTICIPO' : 'DESCUENTO TOTAL' }}:
                        {{ $document->currency_type->symbol }}</td>
                    <td class="text-right font-bold">{{ number_format($document->total_discount, 2) }}</td>
                </tr>
            @endif
            <tr>
                <td colspan="5" class="text-right font-bold">IGV: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold">{{ number_format($document->total_igv, 2) }}</td>
            </tr>
            <tr>
                <td colspan="5" class="text-right font-bold">TOTAL: {{ $document->currency_type->symbol }}
                </td>
                <td class="text-right font-bold">{{ number_format($document->total, 2) }}</td>
            </tr>
        </tbody>
    </table>
    <table class="full-width mt-4">
        <tr>
            <td width="40%">
                <strong>Garantías:</strong>
            </td>
            <td width="60%">
                <strong>Pago y condiciones:</strong>
            </td>
        </tr>
        <tr>
            <td>
                Garantia del servicio : <strong>06 meses</strong>
            </td>
            <td>
                Adelanto <strong>(30%)</strong> al momento de la aceptación, el saldo al finalizar.
            </td>
        </tr>
    </table>

    <table class="full-width">
        <tr>
            <td width="65%">
                <p><strong>Cuentas bancarias:</strong></p>
                <p>Cuenta ahorros soles BBVA Continental &nbsp;&nbsp;:  &nbsp;0011-0153-0200630877-45</p>
                <p>Codigo de cuenta interbancaria CCI &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp; 011-153-000200630877-45</p>
                <p>Cuenta de detracciones BN. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;0021074411
                </p>
            </td>
            <td width="35%" style="text-align: center;" rowspan="2">
                <img width="200px" height="100px"
                    src="data:{{ mime_content_type($firma) }};base64, {{ base64_encode(file_get_contents($firma)) }}">
            </td>
        </tr>
        <tr>
            {{-- <td width="65%">
            @foreach ($document->legends as $row)
                <p>Son: <span class="font-bold">{{ $row->value }} {{ $document->currency_type->description }}</span></p>
            @endforeach
            <br/>
            <strong>Información adicional</strong>
            @foreach ($document->additional_information as $information)
                <p>{{ $information }}</p>
            @endforeach
        </td> --}}
        </tr>
    </table>
</body>

</html>
