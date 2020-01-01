<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'description'];

    public function shoppingList()
    {
        return $this->belongsToMany(ShoppingList::class);
    }
}
