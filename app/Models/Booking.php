<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{

    protected $fillable = [
        'user_id',
        'property_id',
        'start_date',
        'end_date',
        'total_amount',
        'status',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function property(){
        return $this->belongsTo(Property::class);
    }
    public function payment(){
        return $this->hasOne(Payment::class);
    }
    
}
