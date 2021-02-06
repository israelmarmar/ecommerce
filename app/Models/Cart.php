<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    protected $fillable = ['id', 'total', 'user_id'];

    public function products(){
        return $this->hasMany(ProductItem::class, 'cart_id');
    }

    public function purchase(){
        return $this->hasOne(Purchase::class, 'cart_id');
    }

}
