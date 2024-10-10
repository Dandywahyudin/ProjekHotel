<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelPhotos extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'photo',
        'hotels_id',
    ];

    public function hotels(){
        return $this->belongsTo(Hotels::class);
    }
}
