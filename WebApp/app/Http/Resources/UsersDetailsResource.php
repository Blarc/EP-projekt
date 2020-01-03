<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersDetailsResource extends JsonResource
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
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'address' => new AddressResource($this->address),
            'telephone' => $this->telephone,
            'role' => $this->role,
            'apiToken' => $this->api_token,
            'shoppingLists' => ShoppingListsResource::collection($this->shoppingLists)
        ];
    }
}
