<?php
use App\Models\Tenant\Item;
$data = $item->getBarCodeData(15);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <style>

    </style>
</head>

<body>
    @if (!empty($data))
        <div class="">
            <div class=" ">
                <table class="table" width="100%">
                    @php
                        if (!function_exists('withoutRounding')) {
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
                        }
                        $modxd = $data['stocks'] % 2;
                    @endphp

                    @for ($i = 0; $i < $data['stocks']; $i += 2)
                        <tr>
                            <td class="celda" width="45%"
                                style="text-align: center; padding-bottom: 0px; font-size: 9px; vertical-align: top;">
                                <table width="100%" class="table">
                                    <tr>
                                        <td style="text-align: left;">

                                        </td>
                                        <td rowspan="2">
                                            <h2>{{ $data['price'] }}</h2>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;">
                                            <b>{{ substr($data['internal_id'], -10) }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <p>
                                                @php
                                                    $colour = [0, 0, 0];
                                                    $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                                                    echo '<img style="width:150px; max-height: 40px;" src="data:image/png;base64,' . base64_encode($generator->getBarcode($data['barcode'], $generator::TYPE_CODE_128, 3, 80, $colour)) . '">';
                                                @endphp
                                            </p>
                                            <p> {{ $data['brands'] }}-{{ $data['sizes'] }}</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td width="10%">

                            </td>
                            @if ($i < $data['stocks'] - 1)
                                <td class="celda" width="45%"
                                    style="text-align: center;padding-bottom: 0px; font-size: 9px; vertical-align: top;">
                                    <table width="100%" class="table">
                                        <tr>
                                            <td style="text-align: left;">

                                            </td>
                                            <td rowspan="2">
                                                <h2>{{ $data['price'] }}</h2>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">
                                                <b>{{ substr($data['internal_id'], -10) }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <p>
                                                    @php
                                                        $colour = [0, 0, 0];
                                                        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                                                        echo '<img style="width:150px; max-height: 40px;" src="data:image/png;base64,' . base64_encode($generator->getBarcode($data['barcode'], $generator::TYPE_CODE_128, 3, 80, $colour)) . '">';
                                                    @endphp
                                                </p>
                                                <p> {{ $data['brands'] }}-{{ $data['sizes'] }}</p>
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
                                                    <h2>{{ $data['price'] }}</h2>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left;">
                                                    <b>{{ substr($data['internal_id'], -10) }}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <p>
                                                        @php
                                                            $colour = [0, 0, 0];
                                                            $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                                                            echo '<img style="width:150px; max-height: 40px;" src="data:image/png;base64,' . base64_encode($generator->getBarcode($data['barcode'], $generator::TYPE_CODE_128, 3, 80, $colour)) . '">';
                                                        @endphp
                                                    </p>
                                                    <p> {{ $data['brands'] }}-{{ $data['sizes'] }}</p>
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
        <div>
            <p>No se encontraron registros.</p>
        </div>
    @endif
</body>

</html>
