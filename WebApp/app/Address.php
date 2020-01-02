<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
