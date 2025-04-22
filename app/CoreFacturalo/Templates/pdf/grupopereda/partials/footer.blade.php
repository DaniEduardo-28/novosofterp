@php
    $path_style = app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . 'style.css');
@endphp
{{-- 
<head>
    <link href="{{ $path_style }}" rel="stylesheet" />
</head>

<body>
    <table class="full-width">
        <tr>
            <td class="text-center desc font-bold" width="90%">Para consultar el comprobante ingresar a
                {!! url('/buscar') !!}</td>
            <td class="text-right desc font-bold" width="10%">{PAGENO}/{nb}</td>
        </tr>
    </table>
</body> --}}

@php
    $footerq = app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . 'grupopereda' . DIRECTORY_SEPARATOR . 'footer.jpg');
@endphp

<div
    style="position: absolute; top: 87%; left: 0%; border: #000; background-image: url('data:{{ mime_content_type($footerq) }};base64, {{ base64_encode(file_get_contents($footerq)) }}'); background-size: contain; background-position: center; background-repeat: no-repeat; background-size: 100%">
    <br><br><br><br><br><br>
    <table class="full-width" style="top: 0%; left: 50%; transform: translate(-50%, -50%); z-index: 2;">
        <tr>
            <td style="text-align: right; padding-right: 120px;">
                <p>&nbsp;</p>
                <p>www.priserperu.com</p>
            </td>
        </tr>
        <tr>
            <td style="text-align: right; padding-right: 120px;">
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>Pasaje Mártir Olaya N° 129, oficina 1905,<br>
                    distrito de Miraflores, Lima.</p>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
    </table>
</div>
