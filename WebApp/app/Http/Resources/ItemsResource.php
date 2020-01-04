<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        self::withoutWrapping();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'items_amount' => $this->whenPivotLoaded('item_shopping_list', function () {
                return $this->pivot->items_amount;
            })
        ];
    }
}
