<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Order
 */
class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'person' => $this->person,
            'ship_to' => [
                'name' => $this->destination,
                'address' => $this->address,
                'city' => $this->city,
                'country' => $this->country,
            ],
            'items' => OrderItemResource::collection($this->items),
        ];
    }
}
