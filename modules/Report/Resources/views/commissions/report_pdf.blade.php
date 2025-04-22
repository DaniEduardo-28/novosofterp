<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RANKING DE VENDEDORES</title>
    <style>
        html {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-spacing: 0;
            border: 1px solid black;
        }

        .celda {
            text-align: center;
            padding: 5px;
            border: 0.1px solid black;
        }

        th {
            padding: 5px;
            text-align: center;
            border-color: #0088cc;
            border: 0.1px solid black;
        }

        .title {
            font-weight: bold;
            padding: 5px;
            font-size: 20px !important;
            text-decoration: underline;
        }

        p>strong {
            margin-left: 5px;
            font-size: 13px;
        }

        thead {
            font-weight: bold;
            background: #0088cc;
            color: white;
            text-align: center;
        }
    </style>
</head>

<body>
    <div>
        <p align="center" class="title"><strong>REPORTE DE RANKING DE VENDEDORES</strong></p>
    </div>
    <div style="margin-top:20px; margin-bottom:20px;">
        <table>
            <tr>
                <td>
                    <p><strong>EMPRESA: </strong>{{ $company->name }}</p>
                </td>
                <td>
                    <p><strong>FECHA DE REPORTE: </strong>{{ date('d/m/Y H:i:s') }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>RUC: </strong>{{ $company->number }}</p>
                </td>

            </tr>
        </table>
    </div>
    @if (!empty($records))
        <div class="">
            <div class=" ">
                <table class="">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>VENDEDOR</th>
                            <th class="text-center">TRANSACCIONES</th>
                            <th class="text-center">VALOR DE VENTA</th>
                            <th class="text-center">IGV</th>
                            <th class="text-center">TOTAL DE VENTA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                            $subtotal = 0;
                            $igv = 0;
                            $transacciones = 0;
                        @endphp
                        @php
                            $sortedRecords = $records->sortByDesc(function ($row) use ($request) {
                                $data = \Modules\Report\Helpers\UserCommissionHelper::getDataForReportCommission(
                                    $row,
                                    $request,
                                );
                                return floatval(str_replace(',', '', $data['acum_sales']));
                            });
                        @endphp
                        @foreach ($sortedRecords as $row)
                            @php
                                $data = \Modules\Report\Helpers\UserCommissionHelper::getDataForReportCommission(
                                    $row,
                                    $request,
                                );
                                $total_registro = floatval(str_replace(',', '', $data['acum_sales']));
                                $subtotal_registro = $total_registro / 1.18;
                                $igv_registro = $total_registro - $subtotal_registro;

                                $igv += $igv_registro;
                                $subtotal += $subtotal_registro;
                                $total += $total_registro;
                                $transacciones += $data['total_transactions'];
                            @endphp
                            @if ($data['total_transactions'] > 0)
                                <tr>
                                    <td class="celda">{{ $loop->iteration }}</td>
                                    <td class="celda">{{ strtoupper($row->name) }}</td>
                                    <td class="celda">{{ $data['total_transactions'] }}</td>
                                    <td class="celda">{{ number_format($subtotal_registro, 2) }}</td>
                                    <td class="celda">{{ number_format($igv_registro, 2) }}</td>
                                    <td class="celda">{{ number_format($total_registro, 2) }}</td>
                                </tr>
                            @endif
                        @endforeach

                        <tr>
                            <td class="celda" colspan="2">TOTAL GENERAL</td>
                            <td class="celda">{{ $transacciones }}</td>
                            <td class="celda">{{ number_format($subtotal, 2) }}</td>
                            <td class="celda">{{ number_format($igv, 2) }}</td>
                            <td class="celda">{{ number_format($total, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="callout callout-info">
            <p>No se encontraron registros.</p>
        </div>
    @endif
</body>

</html>
