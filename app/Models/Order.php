<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'customer_id',
        'service_type',
        'weight_kg',
        'price_total',
        'status',
        'image_path',
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            $order->uuid = (string) Str::uuid();
        });
    }

    // RELASI
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
