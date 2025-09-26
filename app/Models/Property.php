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
    ];

    public static function statuses(){
        return [ 'available', 'booked', 'unavailable' ];
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
