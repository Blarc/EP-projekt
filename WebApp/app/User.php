<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'password',
        'telephone',
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function generateToken()
    {
        $this->apiToken = Str::random(60);
        $this->save();

        return $this->apiToken;
    }

    public function shoppingLists()
    {
        return $this->hasMany('App\ShoppingList');
    }

    public function address()
    {
        return $this->belongsTo('App\Address');
    }

    public function sellers() {
        return $this->belongsToMany('App\User', 'seller_admin', 'admin_id', 'seller_id')->withTimestamps();
    }

    public function customers() {
        return $this->belongsToMany('App\User', 'customer_seller', 'seller_id', 'customer_id')->withTimestamps();
    }
}
