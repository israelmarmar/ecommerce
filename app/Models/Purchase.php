<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Purchase extends Model
{
    protected $table = 'purchases';

    protected $fillable = ['id', 'buyer_email', 'status', 'cart_id', 'user_id'];

    public function payment(){
        return $this->belongsTo(Payment::class, 'id', 'purchase_id');
    }

    public function cart(){
        return $this->hasOne(Cart::class, 'id', 'cart_id');
    }

}
