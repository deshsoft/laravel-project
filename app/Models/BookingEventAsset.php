<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingEventAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_event_id',
        'fk_asset',
        'asset_qty',
        'asset_price',
        'total'
    ];

    public function bookingEvent()
    {
        return $this->belongsTo(BookingEvent::class, 'booking_event_id');
    }
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'fk_asset', 'id');
    }
}
