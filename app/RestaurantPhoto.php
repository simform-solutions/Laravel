<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RestaurantPhoto extends Model
{
    protected $fillable = [
        'photo'
    ];

    protected $hidden = [
        'restaurant_id', 'created_at', 'updated_at'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }
}
