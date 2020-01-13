<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Item
 *
 * @mixin Illuminate\Database\Eloquent
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $name
 * @property string $description
 * @property float $price
 * @property-read Collection|ShoppingList[] $shoppingList
 * @property-read int|null $shopping_list_count
 * @method static Builder|Item newModelQuery()
 * @method static Builder|Item newQuery()
 * @method static Builder|Item query()
 * @method static Builder|Item whereCreatedAt($value)
 * @method static Builder|Item whereDescription($value)
 * @method static Builder|Item whereId($value)
 * @method static Builder|Item whereName($value)
 * @method static Builder|Item wherePrice($value)
 * @method static Builder|Item whereUpdatedAt($value)
 */
class Item extends Model
{
    protected $fillable = ['name', 'description', 'price'];

    public function shoppingList()
    {
        return $this->belongsToMany(ShoppingList::class)
            ->withPivot('items_amount')
            ->withTimestamps();
    }
}
