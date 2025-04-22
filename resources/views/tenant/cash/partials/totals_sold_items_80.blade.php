@php
    use App\CoreFacturalo\Helpers\Template\ReportHelper;
@endphp

<table class="full-width">
    
    <thead>
        <tr>
            <th colspan="5">Totales por producto</th>
        </tr>
        <tr>
            <th class="border-top-bottom desc-9 text-left" width="8%">#</th>
            <th class="border-top-bottom desc-9 text-left" width="62%">PRODUCTO</th>
            <th class="border-top-bottom desc-9 text-center" width="10%">CAN.</th>
            <th class="border-top-bottom desc-9 text-center" style="text-align: right" width="20%">TOTAL</th>
        </tr>
    </thead>
    <tbody>

        @php
            $sold_items = $documents->whereIn('record_type', ['sale_note_item', 'document_item'])->groupBy('item_id');

            $table_sold_items = $sold_items->map(function($row, $key){

                $last_item = $row->last();
                $description = $last_item['item']->description ?? 'Error al obtener nombre de producto.';
                $unit_value = $last_item['unit_value'];
                $quantity = $row->sum('quantity');
                $total = $row->sum('total');

                return [
                    'item_id' => $last_item['item_id'],
                    'description' => $description,
                    'quantity' => $quantity,
                    'unit_value' => $unit_value,
                    'total' => $total,
                ];
            });

        @endphp

        @foreach ($table_sold_items as $item)
            <tr>
                <td class="border-bottom text-center">{{ $loop->iteration }}</td>
                <td class="border-bottom">{{ $item['description'] }}</td>
                <td class="border-bottom text-center">{{ $item['quantity'] }}</td>
                <td class="border-bottom" style="text-align: right">{{ ReportHelper::setNumber($item['total'], 2, '.', '') }}</td>
            </tr>
        @endforeach

        <tr>
            <td class="" colspan="2"></td>
            <td class="border-bottom text-right"> TOTAL </td>
            <td class="border-bottom" style="text-align: right">
                {{ ReportHelper::setNumber($table_sold_items->sum('total'), 2, '.', '') }}
            </td>
        </tr>
    </tbody>
</table>