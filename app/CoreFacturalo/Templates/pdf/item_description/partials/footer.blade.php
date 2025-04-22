@php
    $path_style = app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . 'style.css');
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
                            @isset($document->qr)
                                @isset($document->hash)
                                    <td width="35%" class="text-right">
                                        <img src="data:image/png;base64, {{ $document->qr }}" style="margin-right: -0px;" />
                                        <p style="font-size: 9px">Código Hash: {{ $document->hash }}</p>
                                    </td>
                                @endisset
                            @endisset
                        </tr>
                    </table>
                @break

                @case('01')
                    <table class="full-width">
                        <tr>
                            <td class="text-center desc font-bold">Representación impresa de la FACTURA ELECTRÓNICA, para
                                consultar el comprobante ingresar a {!! url('/buscar') !!}</td>
                            @isset($document->qr)
                                @isset($document->hash)
                                    <td width="35%" class="text-right">
                                        <img src="data:image/png;base64, {{ $document->qr }}" style="margin-right: -0px;" />
                                        <p style="font-size: 9px">Código Hash: {{ $document->hash }}</p>
                                    </td>
                                @endisset
                            @endisset
                        </tr>
                    </table>
                @break

                @case('07')
                    <table class="full-width">
                        <tr>
                            <td class="text-center desc font-bold">Representación impresa de la NOTA DE CRÉDITO ELECTRÓNICA,
                                para
                                consultar el comprobante ingresar a {!! url('/buscar') !!}</td>
                            @isset($document->qr)
                                @isset($document->hash)
                                    <td width="35%" class="text-right">
                                        <img src="data:image/png;base64, {{ $document->qr }}" style="margin-right: -0px;" />
                                        <p style="font-size: 9px">Código Hash: {{ $document->hash }}</p>
                                    </td>
                                @endisset
                            @endisset
                        </tr>
                    </table>
                @break

                @case('08')
                    <table class="full-width">
                        <tr>
                            <td class="text-center desc font-bold">Representación impresa de la NOTA DE DÉBITO ELECTRÓNICA, para
                                consultar el comprobante ingresar a {!! url('/buscar') !!}</td>
                            @isset($document->qr)
                                @isset($document->hash)
                                    <td width="35%" class="text-right">
                                        <img src="data:image/png;base64, {{ $document->qr }}" style="margin-right: -0px;" />
                                        <p style="font-size: 9px">Código Hash: {{ $document->hash }}</p>
                                    </td>
                                @endisset
                            @endisset
                        </tr>
                    </table>
                @break

                @default
            @endswitch
        @endif
    @endif

</body>
