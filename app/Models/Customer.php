<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'phone',
        'address',
    ];

    protected static function booted()
    {
        static::creating(function ($customer) {
            $customer->uuid = (string) Str::uuid();
        });
    }

    // RELASI
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
