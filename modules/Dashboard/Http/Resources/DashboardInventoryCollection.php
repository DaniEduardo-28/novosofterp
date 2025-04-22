<?php

namespace Modules\Dashboard\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DashboardInventoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {
            $firstLot = $row->item->lots_group->firstWhere('quantity', '>', 0);
            return [
                'id' => $row->id,
                'product' => $row->item->description,
                'stock' => number_format($row->stock, 2, ".", ""),
                'lot' => $firstLot ? $firstLot->code : null,
                'quantity' => $firstLot ? $firstLot->quantity : 0,
                'date' => $firstLot->date_of_due,
                'price' => number_format($row->item->sale_unit_price, 2),
                'warehouse' => $row->warehouse->description,
            ];
        });
    }
}