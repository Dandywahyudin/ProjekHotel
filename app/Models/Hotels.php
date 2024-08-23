<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotels extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'address',
        'link_gmaps',
        'star_level',
        'city_id',
        'country_id',
    ];

    public function city(){
        return $this->belongsTo(City::class);
    }
    public function country(){
        return $this->belongsTo(Country::class);
    }
    public function photos(){
        return $this->hasMany(HotelPhotos::class);
    }
    public function room(){
        return $this->hasMany(HotelRoom::class);
    }
    public function getLowestRoomPrice(){
        $minPrice = $this->room()->min('price');
        return $minPrice ?? 0;
    }
}
