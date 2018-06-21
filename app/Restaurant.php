<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mutable;

/**
 * @property \Grimzy\LaravelMysqlSpatial\Types\Point $location
 */
class Restaurant extends Model
{
    use SpatialTrait, Mutable;
    use Eloquence {
        Eloquence::newEloquentBuilder insteadof SpatialTrait;
        Eloquence::newBaseQueryBuilder insteadof SpatialTrait;
    }

    protected $spatialFields = [
        'location'
    ];

    protected $appends = [
        'current_status'
    ];

    protected $searchableColumns = ['name'];

    protected $getterMutators = [
        'name' => 'ucfirst'
    ];

    protected $setterMutators = [
        'name' => 'strtolower'
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
        return (float)number_format($value, 1);
    }

    public function getPriceRangeAttribute($value)
    {
        return (int)$value;
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

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::createFromTimestamp(strtotime((string)$value))->diffForHumans();
    }

    public static function getMeAllWithTheGivenStatus($column = 'hours_of_operations')
    {
        return self::whereHas('timings', function ($tq) use ($column) {
            $request = \request();
            if ($request->request->has($column) && count($request->get($column)) > 0) {
                $currentDateTimeObj = Carbon::now();

                $from = "STR_TO_DATE(concat('{$currentDateTimeObj->toDateString()}', ' ', from_time), \"%Y-%m-%d %H:%i:%s\")";
                $to = "STR_TO_DATE(concat('{$currentDateTimeObj->toDateString()}', ' ', to_time), \"%Y-%m-%d %H:%i:%s\")";
                $current = "STR_TO_DATE('{$currentDateTimeObj->toDateTimeString()}', \"%Y-%m-%d %H:%i:%s\")";

                $tq->where(function ($mtq) use ($request, $currentDateTimeObj, $from, $to, $current, $column) {
                    foreach ($request->get($column) as $hourOfOperation) {
                        $theHourOfOperationFunction = 'orGetMeAllOpenRestaurants';

                        if ($hourOfOperation === 2) {
                            $theHourOfOperationFunction = 'orGetMeAllClosedRestaurants';
                        } elseif ($hourOfOperation === 3) {
                            $theHourOfOperationFunction = 'orGetMeAllOpeningSoonRestaurants';
                        } elseif ($hourOfOperation === 4) {
                            $theHourOfOperationFunction = 'orGetMeAllClosingSoonRestaurants';
                        }

                        $theHourOfOperationFunction($mtq, $from, $to, $current, $currentDateTimeObj);
                    }
                });
            }
        });
    }
}
