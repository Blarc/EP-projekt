<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShoppingListsResource extends JsonResource
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
            'user_id' => $this->user_id,
            'status' => $this->status
        ];
    }
}
