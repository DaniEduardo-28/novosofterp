<?php

namespace Modules\Item\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubgroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category_id' => $this->category_id,
            'status' => (bool)$this->status
        ];
    }
}
