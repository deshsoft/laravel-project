<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'fk_customer',
        'total_price',
        'discount',
        'discount_percen_flat',
        'final_price',
        'vat_amount',
        'final_price_with_vat',
        'note',
        'created_by',
        'create_date',
        'updated_by',
        'update_date',
    ];

    public function assets()
    {
        return $this->hasMany(BookingEventAsset::class, 'booking_event_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'fk_customer');
    }

    // Define relationship with BookingEventSlot
    public function slots()
    {
        return $this->hasMany(BookingEventSlot::class, 'booking_event_id');
    }
    public function firstSlot()
    {
        return $this->hasOne(BookingEventSlot::class)->orderBy('id'); // or order by date if needed
    }
}
