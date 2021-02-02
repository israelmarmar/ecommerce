<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = ['id', 'name', 'price', 'image', 'quantity', 'user_id'];

}
