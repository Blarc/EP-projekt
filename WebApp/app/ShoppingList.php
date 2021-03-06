<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * App\ShoppingList
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $name
 * @property int $status
 * @property int $user_id
 * @property-read Collection|Item[] $items
 * @property-read int|null $items_count
 * @property-read User $user
 * @method static Builder|ShoppingList newModelQuery()
 * @method static Builder|ShoppingList newQuery()
 * @method static Builder|ShoppingList query()
 * @method static Builder|ShoppingList whereCreatedAt($value)
 * @method static Builder|ShoppingList whereId($value)
 * @method static Builder|ShoppingList whereName($value)
 * @method static Builder|ShoppingList whereStatus($value)
 * @method static Builder|ShoppingList whereUpdatedAt($value)
 * @method static Builder|ShoppingList whereUserId($value)
 * @mixin Eloquent
 */
class ShoppingList extends Model
{
    protected $fillable = ['name', 'user_id', 'status'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }


    /**
     * Create a new controller instance.
     *
     * @return BelongsToMany
     */
    public function items()
    {
        return $this->belongsToMany(Item::class)
            ->withPivot('items_amount')
            ->withTimestamps();
    }

    public function totalAmount(){
        $items = $this->items;
        $amount = 0;
        foreach ($items as $item){
            $amount = $amount + $item->pivot->items_amount * $item->price;
        }
        return $amount;
    }

    public function addItemAndREDIRECT($iid){

        $user = auth()->user();

        if ($user && $user->role == 'customer') {

            if ($this->items()->where('item_id', $iid)->exists()) {
                $items_amount = $this->items()->where('item_id', $iid)->first()->pivot->items_amount;
                $this->items()->updateExistingPivot($iid, array('items_amount' => $items_amount + 1));
            } else {
                $this->items()->attach($iid, array('items_amount' => 1));
            }

            $this->save();
        }
    }

}
