<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

class Restaurant extends Model
{
    use SpatialTrait;

    protected $spatialFields = [
        'location'
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'id');
    }

    public function foodCategories()
    {
        return $this->belongsToMany(FoodCategory::class, 'restaurant_food_categories', 'restaurant_id', 'food_category_id')->withPivot('is_special');
    }
}
