<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemsDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        self::withoutWrapping();
        return [
            'id' => $this -> id,
            'name' => $this -> name,
            'description' => $this -> description
        ];
    }
}