<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = ['id', 'gateway', 'transaction_code', 'purchase_id'];

    public function purchase(){
        return $this->hasOne(Purchase::class);
    }

}
