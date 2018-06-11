<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FoodCategory extends Model
{
    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'restaurant_food_categories', 'food_category_id', 'restaurant_id')->withPivot('is_special');
    }
}
