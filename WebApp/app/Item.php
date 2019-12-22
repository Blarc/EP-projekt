<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name'];

    public function shoppingList()
    {
        return $this->belongsToMany(ShoppingList::class);
    }
}
