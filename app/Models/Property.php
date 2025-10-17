<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyFactory> */
    use HasFactory;


    protected $fillable = [
        'user_id',
        'title',
        'image',
        'video',
        'description',
        'address',
        'city',
        'rent',
        'status',
        'moderation_status',
    ];

    public static function statuses(){
        return [ 'available', 'booked', 'unavailable' ];
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function images(){
        return $this->hasMany(PropertyImage::class);
    }

    public function primaryImage(){
        return $this->hasOne(PropertyImage::class)->where('is_primary', true);
    }

    public function messages(){
        return $this->hasMany(Message::class);
    }

    public function bookings(){
        return $this->hasMany(Booking::class);
    }
}
