@php
    $establishment = $document->establishment;
    $customer = $document->customer;
    $invoice = $document->invoice;
    //$path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
    $document_number = $document->series.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT);
    $accounts = \App\Models\Tenant\BankAccount::all();
    $document_base = ($document->note) ? $document->note : null;
    $payments = $document->payments;

    if($document_base) {
        $affected_document_number = ($document_base->affected_document) ? $document_base->affected_document->series.'-'.str_pad($document_base->affected_document->number, 8, '0', STR_PAD_LEFT) : $document_base->data_affected_document->series.'-'.str_pad($document_base->data_affected_document->number, 8, '0', STR_PAD_LEFT);

    } else {
        $affected_document_number = null;
    }

    $document->load('reference_guides');
    $total_payment = $document->payments->sum('payment');
    $balance = ($document->total - $total_payment) - $document->payments->sum('change');

@endphp
<html>
<head>
    {{--<title>{{ $document_number }}</title>--}}
    {{--<link href="{{ $path_style }}" rel="stylesheet" />--}}
</head>
<body>

@if($company->logo)
    <div class="text-center company_logo_box_sm pt-5 desc-9">
        <img src="data:{{mime_content_type(public_path("storage/uploads/logos/{$company->logo}"))}};base64, {{base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}")))}}" alt="{{$company->name}}" class="company_logo_sm content">
    </div>
{{--@else--}}
    {{--<div class="text-center company_logo_box pt-5">--}}
        {{--<img src="{{ asset('logo/logo.jpg') }}" class="company_logo_ticket contain">--}}
    {{--</div>--}}
@endif
<table class="full-width">
    <tr>
        <td class="text-center"><h4>{{ $company->name }}</h4></td>
    </tr>
    <tr>
        <td class="text-center"><h5>{{ 'RUC '.$company->number }}</h5></td>
    </tr>
    <tr>
        <td class="text-center" style="text-transform: uppercase;">
            {{ ($establishment->address !== '-')? $establishment->address : '' }}
            {{ ($establishment->district_id !== '-')? ', '.$establishment->district->description : '' }}
            {{ ($establishment->province_id !== '-')? ', '.$establishment->province->description : '' }}
            {{ ($establishment->department_id !== '-')? '- '.$establishment->department->description : '' }}
        </td>
    </tr>


    @isset($establishment->trade_address)
    <tr>
        <td class="text-center ">{{  ($establishment->trade_address !== '-')? 'D. Comercial: '.$establishment->trade_address : ''  }}</td>
    </tr>
    @endisset
    <tr>
        <td class="text-center ">{{ ($establishment->telephone !== '-')? 'Central telefónica: '.$establishment->telephone : '' }}</td>
    </tr>
    <tr>
        <td class="text-center">{{ ($establishment->email !== '-')? 'Email: '.$establishment->email : '' }}</td>
    </tr>
    @isset($establishment->web_address)
        <tr>
            <td class="text-center">{{ ($establishment->web_address !== '-')? 'Web: '.$establishment->web_address : '' }}</td>
        </tr>
    @endisset

    @isset($establishment->aditional_information)
        <tr>
            <td class="text-center pb-3">{{ ($establishment->aditional_information !== '-')? $establishment->aditional_information : '' }}</td>
        </tr>
    @endisset

    <tr>
        <td class="text-center pt-3 border-top"><h5>{{ $document->document_type->description }}</h5></td>
    </tr>
    <tr>
        <td class="text-center pb-3 border-bottom"><h5>{{ $document_number }}</h5></td>
    </tr>
</table>
<table class="full-width">
    <tr>
        <td width="" class="pt-3"><p class="desc-9">F. Emisión:</p></td>
        <td width="" class="pt-3 "><p class="desc-9">{{ $document->date_of_issue->format('Y-m-d') }}</p></td>
    </tr>

    @isset($invoice->date_of_due)
    <tr>
        <td><p class="desc-9">F. Vencimiento:</p></td>
        <td><p class="desc-9">{{ $invoice->date_of_due->format('Y-m-d') }}</p></td>
    </tr>
    @endisset

    <tr>
        <td class="align-top"><p class="desc-9">Cliente:</p></td>
        <td><p class="desc-9">{{ $customer->name }}</p></td>
    </tr>
    <tr>
        <td><p class="desc-9">{{ $customer->identity_document_type->description }}:</p></td>
        <td><p class="desc-9">{{ $customer->number }}</p></td>
    </tr>
    @if ($customer->address !== '')
        <tr>
            <td class="align-top"><p class="desc-9">Dirección:</p></td>
            <td>
                <p class="desc-9">
                    {{ $customer->address }}
                    {{ ($customer->district_id !== '-')? ', '.$customer->district->description : '' }}
                    {{ ($customer->province_id !== '-')? ', '.$customer->province->description : '' }}
                    {{ ($customer->department_id !== '-')? '- '.$customer->department->description : '' }}
                </p>
            </td>
        </tr>
    @endif

    
    @if ($document->detraction)
        <tr>
            <td  class="align-top"><p class="desc-9">N. Cta Detracciones:</p></td>
            <td><p class="desc-9">{{ $document->detraction->bank_account}}</p></td>
        </tr>
        <tr>
            <td  class="align-top"><p class="desc-9">B/S Sujeto a detracción:</p></td>
            @inject('detractionType', 'App\Services\DetractionTypeService')
            <td><p class="desc-9">{{$document->detraction->detraction_type_id}} - {{ $detractionType->getDetractionTypeDescription($document->detraction->detraction_type_id ) }}</p></td>
        </tr>
        <tr>
            <td  class="align-top"><p class="desc-9">Método de pago:</p></td>
            <td><p class="desc-9">{{ $detractionType->getPaymentMethodTypeDescription($document->detraction->payment_method_id ) }}</p></td>
        </tr>
        <tr>
            <td  class="align-top"><p class="desc-9">Porcentaje detracción:</p></td>
            <td><p class="desc-9">{{ $document->detraction->percentage}}%</p></td>
        </tr>
        <tr>
            <td  class="align-top"><p class="desc-9">Monto detracción:</p></td>
            <td><p class="desc-9">S/ {{ $document->detraction->amount}}</p></td>
        </tr>
        @if($document->detraction->pay_constancy)
        <tr>
            <td  class="align-top"><p class="desc-9">Constancia de pago:</p></td>
            <td><p class="desc-9">{{ $document->detraction->pay_constancy}}</p></td>
        </tr>
        @endif


        @if($invoice->operation_type_id == '1004')
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="2">DETALLE - SERVICIOS DE TRANSPORTE DE CARGA</td>
        </tr>
        <tr>
            <td class="align-top"><p class="desc-9">Ubigeo origen:</p></td>
            <td><p class="desc-9">{{ $document->detraction->origin_location_id[2] }}</p></td>
        </tr>
        <tr>
            <td  class="align-top"><p class="desc-9">Dirección origen:</td>
            <td><p class="desc-9">{{ $document->detraction->origin_address }}</td>
        </tr>
        <tr>
            <td class="align-top"><p class="desc-9">Ubigeo destino:</p></td>
            <td><p class="desc-9">{{ $document->detraction->delivery_location_id[2] }}</p></td>
        </tr>
        <tr>
    
            <td  class="align-top"><p class="desc-9">Dirección destino:</p></td>
            <td><p class="desc-9">{{ $document->detraction->delivery_address }}</p></td>
        </tr>
        <tr>
            <td class="align-top"><p class="desc-9">Valor referencial servicio de transporte:</p></td>
            <td><p class="desc-9">{{ $document->detraction->reference_value_service }}</p></td>
        </tr>
        <tr>
    
            <td  class="align-top"><p class="desc-9">Valor referencia carga efectiva:</p></td>
            <td><p class="desc-9">{{ $document->detraction->reference_value_effective_load }}</p></td>
        </tr>
        <tr>
            <td class="align-top"><p class="desc-9">Valor referencial carga útil:</p></td>
            <td><p class="desc-9">{{ $document->detraction->reference_value_payload }}</p></td>
        </tr>
        <tr>
            <td  class="align-top"><p class="desc-9">Detalle del viaje:</p></td>
            <td><p class="desc-9">{{ $document->detraction->trip_detail }}</p></td>
        </tr>
        @endif

    @endif

    
    @if ($document->retention)
        <br>    
        <tr>
            <td colspan="2">
                <p class="desc-9"><strong>Información de la retención</strong></p>
            </td>
        </tr>
        <tr>
            <td><p class="desc-9">Base imponible: </p></td>
            <td><p class="desc-9">{{ $document->currency_type->symbol}} {{ $document->retention->base }} </p></td>
        </tr>
        <tr>
            <td><p class="desc-9">Porcentaje:</p></td>
            <td><p class="desc-9">{{ $document->retention->percentage * 100 }}%</p></td>
        </tr>
        <tr>
            <td><p class="desc-9">Monto:</p></td>
            <td><p class="desc-9">{{ $document->currency_type->symbol}} {{ $document->retention->amount }}</p></td>
        </tr>
    @endif



    @if ($document->purchase_order)
        <tr>
            <td><p class="desc-9">Orden de Compra:</p></td>
            <td><p class="desc-9">{{ $document->purchase_order }}</p></td>
        </tr>
    @endif
    @if ($document->quotation_id)
        <tr>
            <td><p class="desc-9">Cotización:</p></td>
            <td><p class="desc-9">{{ $document->quotation->identifier }}</p></td>
        </tr>
    @endif
    @isset($document->quotation->delivery_date)
        <tr>
            <td><p class="desc-9">T. Entrega</p></td>
            <td><p class="desc-9">{{ $document->quotation->delivery_date}}</p></td>
        </tr>
    @endisset
    @isset($document->quotation->sale_opportunity)
        <tr>
            <td><p class="desc-9">O. Venta</p></td>
            <td><p class="desc-9">{{ $document->quotation->sale_opportunity->number_full}}</p></td>
        </tr>
    @endisset
</table>

@if ($document->guides)
{{--<strong>Guías:</strong>--}}
<table>
    @foreach($document->guides as $guide)
        <tr>
            @if(isset($guide->document_type_description))
                <td>{{ $guide->document_type_description }}</td>
            @else
                <td>{{ $guide->document_type_id }}</td>
            @endif
            <td>:</td>
            <td>{{ $guide->number }}</td>
        </tr>
    @endforeach
</table>
@endif

@if (count($document->reference_guides) > 0)
<br/>
<strong>Guias de remisión</strong>
<table>
    @foreach($document->reference_guides as $guide)
        <tr>
            <td>{{ $guide->series }}</td>
            <td>-</td>
            <td>{{ $guide->number }}</td>
        </tr>
    @endforeach
</table>
@endif

@if(!is_null($document_base))
<table>
    <tr>
        <td class="desc-9">Documento Afectado:</td>
        <td class="desc-9">{{ $affected_document_number }}</td>
    </tr>
    <tr>
        <td class="desc-9">Tipo de nota:</td>
        <td class="desc-9">{{ ($document_base->note_type === 'credit')?$document_base->note_credit_type->description:$document_base->note_debit_type->description}}</td>
    </tr>
    <tr>
        <td class="align-top desc">Descripción:</td>
        <td class="text-left desc">{{ $document_base->note_description }}</td>
    </tr>
</table>
@endif

<table class="full-width mt-10 mb-10">
    <thead class="">
    <tr>
        <th class="border-top-bottom desc-9 text-left">CANT.</th>
        <th class="border-top-bottom desc-9 text-left">UNIDAD</th>
        <th class="border-top-bottom desc-9 text-left">DESCRIPCIÓN</th>
        <th class="border-top-bottom desc-9 text-left">P.UNIT</th>
        <th class="border-top-bottom desc-9 text-left">TOTAL</th>
    </tr>
    </thead>
    <tbody>
    @foreach($document->items as $row)
        <tr>
            <td class="text-center desc-9 align-top">
                @if(((int)$row->quantity != $row->quantity))
                    {{ $row->quantity }}
                @else
                    {{ number_format($row->quantity, 0) }}
                @endif
            </td>
            <td class="text-center desc-9 align-top">{{ $row->item->unit_type_id }}</td>
            <td class="text-left desc-9 align-top">
                @if($row->name_product_pdf)
                    {!!$row->name_product_pdf!!}
                @else
                    {!!$row->item->description!!}
                @endif

                @if (!empty($row->item->presentation)) {!!$row->item->presentation->description!!} @endif

                @foreach($row->additional_information as $information)
                    @if ($information)
                        <br/>{{ $information }}
                    @endif
                @endforeach

                @if($row->attributes)
                    @foreach($row->attributes as $attr)
                        <br/>{!! $attr->description !!} : {{ $attr->value }}
                    @endforeach
                @endif
                @if($row->discounts)
                    @foreach($row->discounts as $dtos)
                        <br/><small>{{ $dtos->factor * 100 }}% {{$dtos->description }}</small>
                    @endforeach
                @endif
                @if($row->item->is_set == 1)

                 <br>
                 @inject('itemSet', 'App\Services\ItemSetService')
                 @foreach ($itemSet->getItemsSet($row->item_id) as $item)
                     {{$item}}<br>
                 @endforeach
                 {{-- {{join( "-", $itemSet->getItemsSet($row->item_id) )}} --}}
                @endif
                @if($document->has_prepayment)
                    <br>
                    *** Pago Anticipado ***
                @endif
            </td>
            <td class="text-right desc-9 align-top">{{ number_format($row->unit_price, 2) }}</td>
            <td class="text-right desc-9 align-top">{{ number_format($row->total, 2) }}</td>
        </tr>
        <tr>
            <td colspan="5" class="border-bottom"></td>
        </tr>
    @endforeach
        @if($document->total_exportation > 0)
            <tr>
                <td colspan="4" class="text-right font-bold desc-9">OP. EXPORTACIÓN: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold desc-9">{{ number_format($document->total_exportation, 2) }}</td>
            </tr>
        @endif
        @if($document->total_free > 0)
            <tr>
                <td colspan="4" class="text-right font-bold desc-9">OP. GRATUITAS: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold desc-9">{{ number_format($document->total_free, 2) }}</td>
            </tr>
        @endif
        @if($document->total_unaffected > 0)
            <tr>
                <td colspan="4" class="text-right font-bold desc-9">OP. INAFECTAS: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold desc-9">{{ number_format($document->total_unaffected, 2) }}</td>
            </tr>
        @endif
        @if($document->total_exonerated > 0)
            <tr>
                <td colspan="4" class="text-right font-bold desc-9">OP. EXONERADAS: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold desc-9">{{ number_format($document->total_exonerated, 2) }}</td>
            </tr>
        @endif
        @if($document->total_taxed > 0)
            <tr>
                <td colspan="4" class="text-right font-bold desc-9">OP. GRAVADAS: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold desc">{{ number_format($document->total_taxed, 2) }}</td>
            </tr>
        @endif
         @if($document->total_discount > 0)
            <tr>
                <td colspan="4" class="text-right font-bold desc-9">{{(($document->total_prepayment > 0) ? 'ANTICIPO':'DESCUENTO TOTAL')}}: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold desc">{{ number_format($document->total_discount, 2) }}</td>
            </tr>
        @endif
        @if($document->total_plastic_bag_taxes > 0)
            <tr>
                <td colspan="4" class="text-right font-bold desc">ICBPER: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold desc">{{ number_format($document->total_plastic_bag_taxes, 2) }}</td>
            </tr>
        @endif
        <tr>
            <td colspan="4" class="text-right font-bold desc">IGV: {{ $document->currency_type->symbol }}</td>
            <td class="text-right font-bold desc">{{ number_format($document->total_igv, 2) }}</td>
        </tr>
        <tr>
            <td colspan="4" class="text-right font-bold desc">TOTAL A PAGAR: {{ $document->currency_type->symbol }}</td>
            <td class="text-right font-bold desc">{{ number_format($document->total, 2) }}</td>
        </tr>

        @if($balance < 0)
           <tr>
               <td colspan="5" class="text-right font-bold">VUELTO: {{ $document->currency_type->symbol }}</td>
               <td class="text-right font-bold">{{ number_format(abs($balance),2, ".", "") }}</td>
           </tr>
        @endif
        
        @if(($document->retention || $document->detraction) && $document->total_pending_payment > 0)
            <tr>
                <td colspan="4" class="text-right font-bold desc">M. PENDIENTE: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold desc">{{ number_format($document->total_pending_payment, 2) }}</td>
            </tr>
        @endif
    </tbody>
</table>
<table class="full-width">
    <tr>

        @foreach(array_reverse((array) $document->legends) as $row)
            <tr>
                @if ($row->code == "1000")
                    <td class="desc-9 pt-3">Son: <span class="font-bold">{{ $row->value }} {{ $document->currency_type->description }}</span></td>
                    @if (count((array) $document->legends)>1)
                    <tr><td class="desc-9 pt-3"><span class="font-bold">Leyendas</span></td></tr>
                    @endif
                @else
                    <td class="desc-9 pt-3">{{$row->code}}: {{ $row->value }}</td>
                @endif
            </tr>
        @endforeach
    </tr>


    <tr>
        <td class="desc-9 pt-3">
            @foreach($document->additional_information as $information)
                @if ($information)
                    @if ($loop->first)
                        <strong>Información adicional</strong>
                    @endif
                    <p class="desc-9">{{ $information }}</p>
                @endif
            @endforeach
            <br>
            @if(in_array($document->document_type->id,['01','03']))
                @foreach($accounts as $account)
                    <p>
                    <span class="font-bold">{{$account->bank->description}}</span> {{$account->currency_type->description}}
                    <span class="font-bold">N°:</span> {{$account->number}}
                    @if($account->cci)
                    <span class="font-bold">CCI:</span> {{$account->cci}}
                    @endif
                    </p>
                @endforeach
            @endif

        </td>
    </tr>
    <tr>
        <td class="text-center pt-3 company_logo_box_sm"><img class="content" src="data:image/png;base64, {{ $document->qr }}" /></td>
    </tr>
    <tr>
        <td class="text-center desc">Código Hash: {{ $document->hash }}</td>
    </tr>
    
    
    {{-- Condicion de pago  Crédito / Contado --}}
    @if($document->payment_condition_id)
    <tr>
        <td class="desc-9 pt-5">
            <strong>CONDICIÓN DE PAGO: {{ $document->payment_condition->name }} </strong>
        </td>
    </tr>
    @endif

    @if($document->payment_method_type_id)
        <tr>
            <td class="desc-9 pt-5">
                <strong>MÉTODO DE PAGO: </strong>{{ $document->payment_method_type->description }}
            </td>
        </tr>
    @endif

    @if ($document->payment_condition_id === '01')

        @if($payments->count())
            <tr>
                <td class="desc-9 pt-5">
                    <strong>PAGOS:</strong>
                </td>
            </tr>
            @foreach($payments as $row)
                <tr>
                    <td class="desc-9">&#8226; {{ $row->payment_method_type->description }} - {{ $row->reference ? $row->reference.' - ':'' }} {{ $document->currency_type->symbol }} {{ $row->payment + $row->change }}</td>
                </tr>
            @endforeach
        @endif
    @else
        @foreach($document->fee as $key => $quote)
            <tr>
                <td class="desc-9">&#8226; {{ (empty($quote->getStringPaymentMethodType()) ? 'Cuota #'.( $key + 1) : $quote->getStringPaymentMethodType()) }} / Fecha: {{ $quote->date->format('d-m-Y') }} / Monto: {{ $quote->currency_type->symbol }}{{ $quote->amount }}</td>
            </tr>
        @endforeach
    @endif


    @if($document->payment_method_type_id)
        <tr>
            <td class="desc pt-5">
                <strong>PAGO: </strong>{{ $document->payment_method_type->description }}
            </td>
        </tr> 
    @endif
    {{-- @if($payments->count())
        <tr>
            <td class="desc pt-5">
                <strong>PAGOS:</strong>
            </td>
        </tr>
        @foreach($payments as $row)
            <tr>
                <td class="desc-9">&#8226; {{ $row->payment_method_type->description }} - {{ $row->reference ? $row->reference.' - ':'' }} {{ $document->currency_type->symbol }} {{ $row->payment + $row->change }}</td>
            </tr>
        @endforeach
    @endif --}}
    @if ($document->terms_condition)
    <tr>
        <td class="desc-ticket text-uppercase">
            <br>
            Términos y condiciones del servicio
            <br>
            {!! $document->terms_condition !!}
        </td>
    </tr>
@endif
    @if (class_basename($document) === 'Document')
            @switch($document->document_type_id)
                @case('03')
                    <tr>
                        <td class="text-center desc pt-5">Representación impresa de la BOLETA DE VENTA ELECTRÓNICA, para
                            consultar el comprobante ingresar a {!! url('/buscar') !!}</td>
                    </tr>
                @break

                @case('01')
                    <tr>
                        <td class="text-center desc pt-5">Representación impresa de la FACTURA ELECTRÓNICA, para
                            consultar el comprobante ingresar a {!! url('/buscar') !!}</td>
                    </tr>
                @break

                @case('07')
                    <tr>
                        <td class="text-center desc pt-5">Representación impresa de la NOTA DE CRÉDITO ELECTRÓNICA, para
                            consultar el comprobante ingresar a {!! url('/buscar') !!}</td>
                    </tr>
                @break

                @case('08')
                    <tr>
                        <td class="text-center desc pt-5">Representación impresa de la NOTA DE DÉBITO ELECTRÓNICA, para
                            consultar el comprobante ingresar a {!! url('/buscar') !!}</td>
                    </tr>
                @break

                @default
            @endswitch
        @endif
</table>

</body>
</html>
