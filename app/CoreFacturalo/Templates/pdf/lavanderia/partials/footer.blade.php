@php
    $path_style = app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . 'style.css');
@endphp

<head>
    <link href="{{ $path_style }}" rel="stylesheet" />
</head>

<body>
    <table class="full-width">
        <tr>
            <td class="text-center desc font-bold" style="font-size: 12px;">
                - Después de retirada las prendas los reclamos se hacen dentro de los 7 días hábiles.
				<br>
				- Pasado 1 mes se enviarán las prendas al depósito con un recargo del 30%.
				<br>
				- Pasado los 2 meses pasarán a remate.
            </td>
        </tr>
    </table>
</body>
