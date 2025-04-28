<?php

namespace Modules\Order\Http\Resources;

use Modules\Order\Models\OrderNote;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderNoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $order_note = OrderNote::find($this->id);

        return [
            'id' => $this->id,
            'external_id' => $this->external_id,  
            'identifier' => $this->identifier,
            'date_of_issue' => $this->date_of_issue->format('Y-m-d'),
            'patients_id' => $this->patients_id,
        'patient' => $this->patient ? [
            'id' => $this->patient->id,
            'name' => $this->patient->name,
            'number' => $this->patient->number,
        ] : null,
        'cycles_id' => $this->cycles_id,
        'cycle' => $this->cycle ? [
            'id' => $this->cycle->id,
            'name' => $this->cycle->name,
        ] : null,
        'purchase_order_id' => $this->purchase_order_id,
        'purchase_order' => $this->purchase_order ? [
            'id' => $this->purchase_order->id,
            'full_number' => $this->purchase_order->prefix . '-' . $this->purchase_order->id,
        ] : null,
            'order_note' => $order_note
        ];
    }
}
