<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingEventSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_event_id',
        'from_date',
        'to_date',
        'from_time',
        'to_time',
        'slot_price',
        'aggregable_price',
        'non_aggregable_price',
    ];

    public function bookingEvent()
    {
        return $this->belongsTo(BookingEvent::class, 'booking_event_id');
    }
}
