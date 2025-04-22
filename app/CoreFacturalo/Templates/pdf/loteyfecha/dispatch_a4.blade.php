@php
    $establishment = $document->establishment;
    $customer = $document->customer;
    //$path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
    $accounts = \App\Models\Tenant\BankAccount::where('show_in_documents', true)->get();
    $document_number = $document->series . '-' . str_pad($document->number, 8, '0', STR_PAD_LEFT);
    // $document_type_driver = App\Models\Tenant\Catalogs\IdentityDocumentType::findOrFail($document->driver->identity_document_type_id);
@endphp
<html>

<head>
    {{-- <title>{{ $document_number }}</title> --}}
    {{-- <link href="{{ $path_style }}" rel="stylesheet" /> --}}
</head>

<body>
    <table class="full-width">
        <tr>
            @if ($company->logo)
                <td width="10%">
                    <img src="data:{{ mime_content_type(public_path("storage/uploads/logos/{$company->logo}")) }};base64, {{ base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}"))) }}"
                        alt="{{ $company->name }}" alt="{{ $company->name }}" class="company_logo" style="max-width: 300px">
                </td>
            @else
                <td width="10%">
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
            <td width="40%" class="border-box p-4 text-center">
                <h4 class="text-center">{{ $document->document_type->description }}</h4>
                <h3 class="text-center">{{ $document_number }}</h3>
            </td>
        </tr>
    </table>
    <table class="full-width border-box mt-10 mb-10">
        <thead>
            <tr>
                <th class="border-bottom text-left">DESTINATARIO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Razón Social: {{ $customer->name }}</td>
            </tr>
            <tr>
                <td>{{ $customer->identity_document_type->description }}: {{ $customer->number }}
                </td>
            </tr>
            <tr>
                @if ($document->transfer_reason_type_id === '09')
                    <td>Dirección: {{ $customer->address }} - {{ $customer->country->description }}
                    </td>
                @else
                    <td>Dirección: {{ $customer->address }}
                        {{ $customer->district_id !== '-' ? ', ' . $customer->district->description : '' }}
                        {{ $customer->province_id !== '-' ? ', ' . $customer->province->description : '' }}
                        {{ $customer->department_id !== '-' ? '- ' . $customer->department->description : '' }}
                    </td>
                @endif
            </tr>
            @if ($customer->telephone)
                <tr>
                    <td>Teléfono:{{ $customer->telephone }}</td>
                </tr>
            @endif
            <tr>
                <td>Vendedor: {{ $document->user->name }}</td>
            </tr>
        </tbody>
    </table>
    <table class="full-width border-box mt-10 mb-10">
        <thead>
            <tr>
                <th class="border-bottom text-left" colspan="2">ENVIO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Fecha Emisión: {{ $document->date_of_issue->format('Y-m-d') }}</td>
                <td>Fecha Inicio de Traslado: {{ $document->date_of_shipping->format('Y-m-d') }}</td>
            </tr>
            <tr>
                <td>Motivo Traslado: {{ $document->transfer_reason_type->description }}</td>
                <td>Modalidad de Transporte: {{ $document->transport_mode_type->description }}</td>
            </tr>

            @if ($document->transfer_reason_description)
                <tr>
                    <td colspan="2">Descripción de motivo de traslado: {{ $document->transfer_reason_description }}
                    </td>
                </tr>
            @endif

            @if ($document->related)
                <tr>
                    <td>Número de documento (DAM): {{ $document->related->number }}</td>
                    <td>Tipo documento relacionado: {{ $document->getRelatedDocumentTypeDescription() }}</td>
                </tr>
            @endif

            <tr>
                <td>Peso Bruto Total({{ $document->unit_type_id }}): {{ $document->total_weight }}</td>
                @if ($document->packages_number)
                    <td>Número de Bultos: {{ $document->packages_number }}</td>
                @endif
            </tr>
            <tr>
                <td colspan="2">P.Partida: {{ $document->origin->location_id }} - {{ $document->origin->address }}
                </td>
            </tr>
            <tr>
                <td colspan="2">P.Llegada: {{ $document->delivery->location_id }} -
                    {{ $document->delivery->address }}</td>
            </tr>
            @if ($document->order_form_external)
                <tr>
                    <td>Orden de pedido: {{ $document->order_form_external }}</td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>
    <table class="full-width border-box mt-10 mb-10">
        <thead>
            <tr>
                <th class="border-bottom text-left" colspan="2">TRANSPORTE</th>
            </tr>
        </thead>
        <tbody>
            @if ($document->transport_mode_type_id === '01')
                @php
                    $document_type_dispatcher = App\Models\Tenant\Catalogs\IdentityDocumentType::findOrFail($document->dispatcher->identity_document_type_id);
                @endphp
                <tr>
                    <td>Nombre y/o razón social: {{ $document->dispatcher->name }}</td>
                    <td>{{ $document_type_dispatcher->description }}: {{ $document->dispatcher->number }}</td>
                </tr>
            @else
                <tr>
                    @if ($document->transport_data)
                        <td>Número de placa del vehículo: {{ $document->transport_data['plate_number'] }}</td>
                    @endif
                    @if ($document->driver->number)
                        <td>Conductor: {{ $document->driver->number }}</td>
                    @endif
                </tr>
                <tr>
                    @if ($document->secondary_license_plates)
                        @if ($document->secondary_license_plates->semitrailer)
                            <td>Número de placa semirremolque: {{ $document->secondary_license_plates->semitrailer }}
                            </td>
                        @endif
                    @endif
                    @if ($document->driver->license)
                        <td>Licencia del conductor: {{ $document->driver->license }}</td>
                    @endif
                </tr>
            @endif
        </tbody>
    </table>
    <table class="full-width mt-10 mb-10">
        <thead class="">
            <tr class="bg-grey">
                <th class="border-top-bottom py-2 text-center" width="8%">#</th>
                <th class="border-top-bottom py-2 text-left">Descripción</th>
                <th class="border-top-bottom text-center py-2" width="10%">LOTE</th>
                <th class="border-top-bottom text-center py-2" width="10%">F. VENC</th>
                <th class="border-top-bottom py-2 text-center" width="8%">UND</th>
                <th class="border-top-bottom py-2 text-center" width="8%">CANT</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($document->items as $row)
                @php
                    $itemxd = \App\Models\Tenant\Item::find($row->item_id);
                @endphp
                <tr>
                    <td class="text-center py-2">{{ $loop->iteration }}</td>
                    <td class="text-left py-2 align-top">
                        {{-- @if ($row->name_product_pdf)
                            {!! $row->name_product_pdf . ' (' . $itemxd->internal_id . ')' !!}
                        @else
                            {{ $row->item->description . ' (' . $itemxd->internal_id . ')' }}
                        @endif --}}

                        {{ $row->item->description . ' (' . $itemxd->internal_id . ')' }}

                        @if ($itemxd->brand->name)
                            <p style="font-size: 9px">
                                <strong>Marca: </strong>
                                {{ $itemxd->brand->name }}
                            </p>
                        @endif

                        @if ($itemxd->procedencia)
                            <p style="font-size: 9px">
                                <strong>Procedencia: </strong>
                                {!! $itemxd->procedencia !!}
                            </p>
                        @endif

                        @if ($itemxd->pais)
                            <p style="font-size: 9px">
                                <strong>País: </strong>
                                {!! $itemxd->pais !!}
                            </p>
                        @endif

                        @inject('itemLotGroup', 'App\Services\ItemLotsGroupService')
                        <p style="font-size: 9px">
                            @if ($document->reference_document)
                                @foreach ($document->reference_document->items as $itemdd)
                                    @if ($row->item_id === $itemdd->item_id)
                                        <strong>Lote:
                                        </strong>{{ strtoupper($itemLotGroup->getLote($itemdd->item->IdLoteSelected)) }}
                                        <br>
                                        @php
                                            $fechaOriginal = $itemLotGroup->getLotDateOfDue($itemdd->item->IdLoteSelected);
                                            $fechaObjeto = new DateTime($fechaOriginal);
                                            $fechaFormateada = $fechaObjeto->format('d/m/Y');
                                        @endphp
                                        <strong>F. Venc: </strong>{{ $fechaFormateada }}
                                    @endif
                                @endforeach
                            @endif
                        </p>

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
                        @if ($row->relation_item->is_set == 1)
                            <br>
                            @inject('itemSet', 'App\Services\ItemSetService')
                            @foreach ($itemSet->getItemsSet($row->item_id) as $item)
                                {{ $item }}<br>
                            @endforeach
                        @endif

                        @if ($document->has_prepayment)
                            <br>
                            *** Pago Anticipado ***
                        @endif
                    </td>
                    <td class="text-center align-top" style="font-size: 10px">
                        @inject('itemLotGroup', 'App\Services\ItemLotsGroupService')
                        {{ strtoupper($itemLotGroup->getLote($row->item->IdLoteSelected)) }}
                    </td>

                    <td class="text-center align-top" style="font-size: 10px">
                        @php
                            $result = '';
                            if (is_array($row->item->IdLoteSelected)) {
                                $id = $row->item->IdLoteSelected;

                                foreach ($id as $item) {
                                    if ($item->date_of_due) {
                                        $result .= $item->date_of_due . ' ';
                                    }
                                }
                            }
                            $fechaOriginal = $itemLotGroup->getLotDateOfDue($row->item->IdLoteSelected);
                            $fechaObjeto = new DateTime($fechaOriginal);
                            $fechaFormateada = $fechaObjeto->format('d/m/Y');
                        @endphp

                        @if ($result !== '')
                            {{ $fechaFormateada }}
                        @endif
                    </td>
                    <td class="text-center py-2">{{ $row->item->unit_type_id }}</td>
                    <td class="text-center py-2">
                        @if ((int) $row->quantity != $row->quantity)
                            {{ $row->quantity }}
                        @else
                            {{ number_format($row->quantity, 0) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="6" class="border-bottom"></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($document->observations)
        <table class="full-width border-box mt-10 mb-10">
            <tr>
                <td class="text-bold border-bottom font-bold">OBSERVACIONES</td>
            </tr>
            <tr>
                <td>{{ $document->observations }}</td>
            </tr>
        </table>
    @endif

    @if ($document->reference_document)
        <table class="full-width border-box">
            @if ($document->reference_document)
                <tr>
                    <td class="text-bold border-bottom font-bold">
                        {{ $document->reference_document->document_type->description }}</td>
                </tr>
                <tr>
                    <td>{{ $document->reference_document ? $document->reference_document->number_full : '' }}</td>
                </tr>
            @endif
        </table>
    @endif
    @if ($document->data_affected_document)
        @php
            $document_data_affected_document = $document->data_affected_document;

            $number = property_exists($document_data_affected_document, 'number') ? $document_data_affected_document->number : null;
            $series = property_exists($document_data_affected_document, 'series') ? $document_data_affected_document->series : null;
            $document_type_id = property_exists($document_data_affected_document, 'document_type_id') ? $document_data_affected_document->document_type_id : null;

        @endphp
        @if ($number !== null && $series !== null && $document_type_id !== null)
            @php
                $documentType = App\Models\Tenant\Catalogs\DocumentType::find($document_type_id);
                $textDocumentType = $documentType->getDescription();
            @endphp
            <table class="full-width border-box">
                <tr>
                    <td class="text-bold border-bottom font-bold">{{ $textDocumentType }}</td>
                </tr>
                <tr>
                    <td>{{ $series }}-{{ $number }}</td>
                </tr>
            </table>
        @endif
    @endif
    @if ($document->reference_order_form_id)
        <table class="full-width border-box">
            @if ($document->order_form)
                <tr>
                    <td class="text-bold border-bottom font-bold">ORDEN DE PEDIDO</td>
                </tr>
                <tr>
                    <td>{{ $document->order_form ? $document->order_form->number_full : '' }}</td>
                </tr>
            @endif
        </table>
    @elseif ($document->order_form_external)
        <table class="full-width border-box">
            <tr>
                <td class="text-bold border-bottom font-bold">ORDEN DE PEDIDO</td>
            </tr>
            <tr>
                <td>{{ $document->order_form_external }}</td>
            </tr>
        </table>

    @endif


    @if ($document->reference_sale_note_id)
        <table class="full-width border-box">
            @if ($document->sale_note)
                <tr>
                    <td class="text-bold border-bottom font-bold">NOTA DE VENTA</td>
                </tr>
                <tr>
                    <td>{{ $document->sale_note ? $document->sale_note->number_full : '' }}</td>
                </tr>
            @endif
        </table>
    @endif
    @if ($document->qr)
        <table class="full-width">
            <tr>
                <td class="text-left">
                    <img src="data:image/png;base64, {{ $document->qr }}" style="margin-right: -10px;" />
                </td>
                <td>
                    <table class="full-width">
                        <tr class="bg-grey">
                            <td class="text-center border-box" colspan="3">
                                <strong>7M DROGUERIAS PERUANAS S.A.C.</strong>
                            </td>
                        </tr>
                        <tr class="bg-grey">
                            <td width="35%" class="border-box">Entidad Bancaria</td>
                            <td width="25%" class="border-box text-center">N° Cuenta</td>
                            <td width="40%" class="border-box text-center">CCI</td>
                        </tr>
                        @foreach ($accounts as $account)
                            <tr>
                                <td class="border-box" style="font-size: 9px">{{ $account->bank->description }}</td>
                                <td class="border-box" style="font-size: 9px">{{ $account->number }}</td>
                                <td class="border-box" style="font-size: 9px">{{ $account->cci }}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
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

</body>

</html>
