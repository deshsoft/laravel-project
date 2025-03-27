<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_type',
        'mode',
        'asset_size',
        'available_quantity',
        'rental_value',
        'fixed_hourly',
    ];
}
