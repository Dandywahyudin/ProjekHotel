<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelRoom extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'photo',
        'price',
        'total_price',
        'hotel_id',
    ];

    public function hotel() {
        return $this->belongsTo(Hotels::class, 'hotel_id');
    }
}
