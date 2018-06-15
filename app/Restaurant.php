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
        return (float) number_format($value, 1);
    }

    public function getPriceRangeAttribute($value)
    {
        return (int) $value;
    }

    public function getCurrentStatusAttribute()
    {
        $theStatus = 2;
        $allTimings = $this->timings;
        $currentDateTimeObj = Carbon::now();
        $todaySchedules = $allTimings->where('day_of_week', '=', $currentDateTimeObj->dayOfWeek);

        if ($todaySchedules->count() > 0) {
            foreach ($todaySchedules as $todaySchedule) {
                $fromDateTimeObj = $currentDateTimeObj->copy()->setTimeFromTimeString($todaySchedule->from_time);
                $toDateTimeObj = $currentDateTimeObj->copy()->setTimeFromTimeString($todaySchedule->to_time);

                if ($fromDateTimeObj->lessThanOrEqualTo($currentDateTimeObj) && $toDateTimeObj->copy()->subHour()->greaterThan($currentDateTimeObj)) {
                    $theStatus = 1;
                    break;
                } elseif ($fromDateTimeObj->greaterThan($currentDateTimeObj) && $currentDateTimeObj->copy()->addHour()->greaterThan($fromDateTimeObj)) {
                    $theStatus = 3;
                    break;
                } elseif ($toDateTimeObj->greaterThan($currentDateTimeObj) && $currentDateTimeObj->copy()->addHour()->greaterThan($toDateTimeObj)) {
                    $theStatus = 4;
                    break;
                }
            }
        }

        return $theStatus;
    }

    public function getPhotoAttribute($value)
    {
        return $value ?: \asset(\env('RESTAURANT_DEFAULT_IMAGE'));
    }

    public function getLocationAttribute($value)
    {
        return [
            'latitude' => $value->getLat(),
            'longitude' => $value->getLng()
        ];
    }

    public function photos()
    {
        return $this->hasMany(RestaurantPhoto::class, 'restaurant_id', 'id');
    }
}
