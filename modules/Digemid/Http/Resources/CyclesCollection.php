<?php

    namespace Modules\Digemid\Http\Resources;

    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\ResourceCollection;
    use Modules\Digemid\Models\Cycles;

    class CyclesCollection extends ResourceCollection
    {
        /**
         * Transform the resource collection into an array.
         *
         * @param Request $request
         *
         * @return array
         */
        public function toArray($request)
        {
            return $this->collection->transform(function (Cycles $row, $key) {

                return [
                    'id' => $row->id,
                    'name' => $row->name,
                    'status' => (bool) $row->status
                ];
            });

        }

    }
