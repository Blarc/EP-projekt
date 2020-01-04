<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Address
 *
 * @property int $id
 * @property string $street
 * @property string $post
 * @property string $postCode
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|User[] $user
 * @property-read int|null $user_count
 * @method static Builder|Address newModelQuery()
 * @method static Builder|Address newQuery()
 * @method static Builder|Address query()
 * @method static Builder|Address whereCreatedAt($value)
 * @method static Builder|Address whereId($value)
 * @method static Builder|Address wherePost($value)
 * @method static Builder|Address wherePostCode($value)
 * @method static Builder|Address whereStreet($value)
 * @method static Builder|Address whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Address extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'street',
        'post',
        'postCode',
    ];

    public function user()
    {
        return $this->hasMany('App\User');
    }
}
