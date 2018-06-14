<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

/**
 * @property \Grimzy\LaravelMysqlSpatial\Types\Point $location
 */
class Restaurant extends Model
{
    use SpatialTrait;

    protected $spatialFields = [
        'location'
    ];

    protected $appends = [
        'current_status'
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'id');
    }

    public function foodCategories()
    {
        return $this->belongsToMany(FoodCategory::class, 'restaurant_food_categories', 'restaurant_id', 'food_category_id')->withPivot('is_special');
    }

    public function scopeDistance($query, $geometryColumn, $geometry, $distance)
    {
        $mFactor = env('MULTIPLY_FACTOR_FOR_DEGREE_TO_MILES');
        $distanceCalculation = "st_distance(`{$geometryColumn}`, ST_GeomFromText('{$geometry->toWkt()}')) * {$mFactor}";
        return $query->selectRaw("*, {$distanceCalculation} as distance")->whereRaw("{$distanceCalculation} <= {$distance}")->orderBy('distance');
    }

    public function timings()
    {
        return $this->hasMany(RestaurantTiming::class, 'restaurant_id', 'id');
    }

    public function getDistanceAttribute($value)
    {
        return number_format($value, 1);
    }

    public function getCurrentStatusAttribute()
    {
        $allTimings = $this->timings;
        dd($allTimings->where('day_of_week', '=', Carbon::now()->dayOfWeek));
    }
}
