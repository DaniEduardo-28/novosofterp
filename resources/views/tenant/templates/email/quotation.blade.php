<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Envio de Cotización</title>
    <style>
        body {
            color: #000;
        }

        ul {
            list-style: none;
        }
    </style>
</head>

<body>
    <p>Estimad@:
        @if ($quotation->customer)
            {{ $quotation->customer->name }}
        @else
            {{ $quotation->supplier->name }}
        @endif
        , informamos que su cotización ha sido generada exitosamente.
    </p>
    <p>Los datos de su cotización son:</p>
    <ul>
        <li>Razon social: {{ $company->name }}</li>
        <li>Teléfono: {{ $quotation->establishment->telephone }}</li>
        <li>Fecha de emisión: {{ $quotation->date_of_issue->format('d/m/Y') }}</li>
        <li>Nro. de comprobante: {{ $quotation->prefix . '-' . $quotation->id }}</li>
        <li>Total: {{ $quotation->total }}</li>
    </ul>
</body>

</html>
