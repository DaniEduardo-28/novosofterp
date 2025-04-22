<!DOCTYPE html>
<html lang="es">

<head>

</head>

<body>
    @if (!empty($record))
        @if ($format != 4)
            <div class="">
                <div class=" ">
                    <table class="table" width="100%">
                        @php
                            function withoutRounding($number, $total_decimals)
                            {
                                $number = (string) $number;
                                if ($number === '') {
                                    $number = '0';
                                }
                                if (strpos($number, '.') === false) {
                                    $number .= '.';
                                }
                                $number_arr = explode('.', $number);

                                $decimals = substr($number_arr[1], 0, $total_decimals);
                                if ($decimals === false) {
                                    $decimals = '0';
                                }

                                $return = '';
                                if ($total_decimals == 0) {
                                    $return = $number_arr[0];
                                } else {
                                    if (strlen($decimals) < $total_decimals) {
                                        $decimals = str_pad($decimals, $total_decimals, '0', STR_PAD_RIGHT);
                                    }
                                    $return = $number_arr[0] . '.' . $decimals;
                                }
                                return $return;
                            }

                            $modxd = $stock % 2;
                            if ($stock > 800) {
                                $stock = 800;
                            }

                        @endphp

                        @for ($i = 0; $i < $stock; $i += 2)
                            <tr>
                                <td class="celda" width="45%"
                                    style="text-align: center; padding-bottom: 0px; font-size: 9px; vertical-align: top;">
                                    <table width="100%" class="table">
                                        <tr>
                                            <td style="text-align: left;">

                                            </td>
                                            <td rowspan="2">
                                                <h2>{{ $record->currency_type->symbol }}
                                                    {{ round($record->sale_unit_price, 2) }}</h2>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">
                                                <b>{{ substr($record->internal_id, -10) }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <p>
                                                    @php
                                                        $colour = [0, 0, 0];
                                                        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                                                        echo '<img style="width:150px; max-height: 40px;" src="data:image/png;base64,' .
                                                            base64_encode(
                                                                $generator->getBarcode(
                                                                    $record->barcode,
                                                                    $generator::TYPE_CODE_128,
                                                                    3,
                                                                    80,
                                                                    $colour,
                                                                ),
                                                            ) .
                                                            '">';
                                                    @endphp
                                                </p>
                                                <p> {{ $record->brand->name }}-{{ $record->size }}</p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="10%">

                                </td>
                                @if ($i < $stock - 1)
                                    <td class="celda" width="45%"
                                        style="text-align: center;padding-bottom: 0px; font-size: 9px; vertical-align: top;">
                                        <table width="100%" class="table">
                                            <tr>
                                                <td style="text-align: left;">

                                                </td>
                                                <td rowspan="2">
                                                    <h2>{{ $record->currency_type->symbol }}
                                                        {{ round($record->sale_unit_price, 2) }}</h2>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left;">
                                                    <b>{{ substr($record->internal_id, -10) }}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <p>
                                                        @php
                                                            $colour = [0, 0, 0];
                                                            $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                                                            echo '<img style="width:150px; max-height: 40px;" src="data:image/png;base64,' .
                                                                base64_encode(
                                                                    $generator->getBarcode(
                                                                        $record->barcode,
                                                                        $generator::TYPE_CODE_128,
                                                                        3,
                                                                        80,
                                                                        $colour,
                                                                    ),
                                                                ) .
                                                                '">';
                                                        @endphp
                                                    </p>
                                                    <p> {{ $record->brand->name }}-{{ $record->size }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                @else
                                    @if ($modxd == 0)
                                        <td class="celda" width="45%"
                                            style="text-align: center;padding-bottom: 0px; font-size: 9px; vertical-align: top;">
                                            <table width="100%" class="table">
                                                <tr>
                                                    <td style="text-align: left;">

                                                    </td>
                                                    <td rowspan="2">
                                                        <h2>{{ $record->currency_type->symbol }}
                                                            {{ round($record->sale_unit_price, 2) }}</h2>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left;">
                                                        <b>{{ substr($record->internal_id, -10) }}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <p>
                                                            @php
                                                                $colour = [0, 0, 0];
                                                                $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                                                                echo '<img style="width:150px; max-height: 40px;" src="data:image/png;base64,' .
                                                                    base64_encode(
                                                                        $generator->getBarcode(
                                                                            $record->barcode,
                                                                            $generator::TYPE_CODE_128,
                                                                            3,
                                                                            80,
                                                                            $colour,
                                                                        ),
                                                                    ) .
                                                                    '">';
                                                            @endphp
                                                        </p>
                                                        <p> {{ $record->brand->name }}-{{ $record->size }}</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    @else
                                        <td class="celda" width="45%">

                                        </td>
                                    @endif
                                @endif
                            </tr>
                        @endfor

                    </table>
                </div>
            </div>
        @else
            @php
                $limitedDescription = \Illuminate\Support\Str::limit($record->description, 40);
                $colour = [0, 0, 0];
                $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                $barcodeImage = $generator->getBarcode($record->barcode, $generator::TYPE_CODE_128, 3, 2000, $colour);
                $barcodeBase64 = base64_encode($barcodeImage);
            @endphp

            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="width: 49%; border: 1px solid;">
                        <table width="100%">
                            <tr>
                                <td
                                    style="height: 2em; line-height: 0.75em; overflow: hidden; word-wrap: break-word; vertical-align: middle;">
                                    <p style="margin: 0; max-height: 2em; overflow: hidden; font-size: 8px;">
                                        {{ $limitedDescription }}
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <table width="100%" style="border-top: 1px solid !important;">
                            <tr>
                                <td style="overflow: hidden; word-wrap: break-word; vertical-align: middle;">
                                    <p style="margin: 0; font-size: 8px;">{{ date('d/m/Y') }}</p>
                                </td>
                                <td rowspan="3"
                                    style="height: 3em; line-height: 0.95em; overflow: hidden; word-wrap: break-word; vertical-align: middle; text-align: center;">
                                    <h3>{{ $record->currency_type->symbol }}
                                        {{ number_format($record->sale_unit_price, 2) }}</h3>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>
                                        <img style="width: 100px; height: 35px;"
                                            src="data:image/png;base64,{{ $barcodeBase64 }}">
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td style="overflow: hidden; word-wrap: break-word; vertical-align: middle;">
                                    <p style="margin: 0; font-size: 8px;">{{ $record->barcode }}</p>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td style="width: 2%;">

                    </td>

                    <td style="width: 49%; border: 1px solid;">
                        <table width="100%">
                            <tr>
                                <td
                                    style="height: 2em; line-height: 0.75em; overflow: hidden; word-wrap: break-word; vertical-align: middle;">
                                    <p style="margin: 0; max-height: 2em; overflow: hidden; font-size: 8px;">
                                        {{ $limitedDescription }}
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <table width="100%" style="border-top: 1px solid !important;">
                            <tr>
                                <td style="overflow: hidden; word-wrap: break-word; vertical-align: middle;">
                                    <p style="margin: 0; font-size: 8px;">{{ date('d/m/Y') }}</p>
                                </td>
                                <td rowspan="3"
                                    style="height: 3em; line-height: 0.95em; overflow: hidden; word-wrap: break-word; vertical-align: middle; text-align: center;">
                                    <h3>{{ $record->currency_type->symbol }}
                                        {{ number_format($record->sale_unit_price, 2) }}</h3>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>
                                        <img style="width: 100px; height: 35px;"
                                            src="data:image/png;base64,{{ $barcodeBase64 }}">
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td style="overflow: hidden; word-wrap: break-word; vertical-align: middle;">
                                    <p style="margin: 0; font-size: 8px;">{{ $record->barcode }}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>


        @endif
    @else
        <div>
            <p>No se encontraron registros.</p>
        </div>
    @endif
</body>

</html>
