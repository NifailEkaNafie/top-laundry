<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_name',
        'service_type',
        'weight_kg',
        'price_total',
        'status',
        'image_path',
    ];
}
