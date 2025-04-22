<?php

namespace App\Http\Resources\Tenant;

use App\Models\System\User;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Item;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PosCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function ($row, $key) {

            $configuration = Configuration::first();
            $sale_unit_price = $this->getSaleUnitPrice($row, $configuration);
            $lotesConCantidad = $row->lots_group()
                ->where('quantity', '>', 0)
                ->orderBy('date_of_due', 'asc') // Ordenar por fecha de vencimiento en la consulta
                ->get()
                ->transform(function ($lot) {
                    $isExpiringSoon = (new \DateTime($lot->date_of_due) < (new \DateTime())->modify('+3 months'));
                    return [
                        'id' => $lot->id,
                        'code' => $lot->code,
                        'code_string' => $isExpiringSoon ? '<span style="color:red">' . $lot->code . '</span>' : $lot->code,
                        'quantity' => $lot->quantity,
                        'date_of_due' => $isExpiringSoon ? '<span style="color:red">' . ($lot->date_of_due ? $lot->date_of_due : '') . '</span>' : ($lot->date_of_due ? $lot->date_of_due : ''),
                    ];
                });

            return [
                'stock' => $row->getStockByWarehouse(),
                'id' => $row->id,
                'item_id' => $row->id,
                'full_description' => ($row->internal_id) ? $row->internal_id . ' - ' . $row->description : $row->description,
                'name' => $row->name,
                'second_name' => $row->second_name,
                'description' => (($row->brand->name) ? $row->description . ' - ' . $row->brand->name : $row->description) . " " . $row->concentracion,
                'currency_type_id' => $row->currency_type_id,
                'internal_id' => $row->internal_id,
                'currency_type_symbol' => $row->currency_type->symbol,
                'sale_unit_price' => $sale_unit_price,
                'purchase_unit_price' => $row->purchase_unit_price,
                'principio_activo' => $row->principio_activo,
                'concentracion' => $row->concentracion,
                'accion_farmacologica' => $row->accion_farmacologica,
                'unit_type_id' => $row->unit_type_id,
                'aux_unit_type_id' => $row->unit_type_id,
                'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                'calculate_quantity' => (bool) $row->calculate_quantity,
                'has_igv' => (bool) $row->has_igv,
                'is_set' => (bool) $row->is_set,
                'active' => $row->active,
                'edit_unit_price' => false,
                'aux_quantity' => 1,
                'edit_sale_unit_price' => $sale_unit_price,
                'aux_sale_unit_price' => $sale_unit_price,
                'image_url' => ($row->image !== 'imagen-no-disponible.jpg') ? asset('storage' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'items' . DIRECTORY_SEPARATOR . $row->image) : asset("/logo/{$row->image}"),
                'warehouses' => collect($row->warehouses)->transform(function ($row) {
                    return [
                        'warehouse_description' => $row->warehouse->description,
                        'stock' => $row->stock,
                    ];
                }),
                'category_id' => ($row->category) ? $row->category->id : null,
                'sets' => collect($row->sets)->transform(function ($r) {
                    return [
                        $r->individual_item->description,
                    ];
                }),
                'unit_type' => $this->getUnitTypeItems($row->item_unit_types),
                'category' => ($row->category) ? $row->category->name : null,
                'brand' => ($row->brand) ? $row->brand->name : null,
                'has_plastic_bag_taxes' => (bool) $row->has_plastic_bag_taxes,
                'amount_plastic_bag_taxes' => $row->amount_plastic_bag_taxes,

                'has_plastic_bag_taxes' => (bool) $row->has_plastic_bag_taxes,

                'has_isc' => (bool)$row->has_isc,
                'system_isc_type_id' => $row->system_isc_type_id,
                'percentage_isc' => $row->percentage_isc,

                'exchange_points' => $row->exchange_points,
                'quantity_of_points' => $row->quantity_of_points,
                'exchanged_for_points' => false,
                'used_points_for_exchange' => null,
                'original_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                'restrict_sale_cpe' => $row->restrict_sale_cpe,
                'laboratorio' => $row->laboratorio,
                'lots_farmacia' => json_decode($lotesConCantidad)
            ];
        });
    }


    private function getSaleUnitPrice($row, $configuration)
    {

        $sale_unit_price = number_format($row->sale_unit_price, $configuration->decimal_quantity, ".", "");

        if ($configuration->active_warehouse_prices) {

            $warehouse_price = $row->warehousePrices()->where('warehouse_id', auth()->user()->establishment->warehouse->id)->first();

            if ($warehouse_price) {

                $sale_unit_price = number_format($warehouse_price->price, $configuration->decimal_quantity, ".", "");
            } else {

                if ($row->warehousePrices()->count() > 0) {
                    $sale_unit_price = number_format($row->warehousePrices()->first()->price, $configuration->decimal_quantity, ".", "");
                }
            }
        }

        return $sale_unit_price;
    }

    private function getUnitTypeItems($unit_types)
    {
        $unittypereturn = [];
        foreach ($unit_types as $unit_type) {
            $flag = false;
            if (auth()->user()->permisosLt !== null && auth()->user()->permisosLt !== "null") {
                $lp = json_decode(auth()->user()->permisosLt);
                foreach ($lp as $p) {
                    if ($p->description == $unit_type->description) {
                        $flag = true;
                    }
                }
            }
            if ($flag) {
                $unittypereturn[] = $unit_type;
            }
        }
        return $unittypereturn;
    }
}
