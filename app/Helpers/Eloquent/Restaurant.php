<?php

function mustNotBeAnOpenRestaurant(&$query)
{
    $from = \func_get_arg(1);
    $to = \func_get_arg(2);
    $current = \func_get_arg(3);
    $currentDateTimeObj = \func_get_arg(4);

    $query->whereNotExists(function ($subQuery) use ($from, $to, $current, $currentDateTimeObj) {
        $subQuery->from('restaurant_timings')
                 ->whereRaw('`restaurants`.`id` = `restaurant_timings`.`restaurant_id`')
                 ->whereDayOfWeek($currentDateTimeObj->dayOfWeek)
                 ->whereRaw("{$from} <= {$current}")
                 ->whereRaw("{$to} > {$current}");
    });
}

function mustBeClosedRestaurants(&$query)
{
    $from = \func_get_arg(1);
    $to = \func_get_arg(2);
    $current = \func_get_arg(3);
    $currentDateTimeObj = \func_get_arg(4);

    $query->whereDayOfWeek($currentDateTimeObj->dayOfWeek)
          ->where(function ($subQuery) use ($from, $to, $current) {
            $subQuery->whereRaw("{$from} > {$current}")->orWhereRaw("{$to} <= {$current}");
          });
    mustNotBeAnOpenRestaurant($query, $from, $to, $current, $currentDateTimeObj);
}

function mustOpenAtLeastOnceToday(&$query)
{
    $currentDateTimeObj = \func_get_arg(1);
    $query->from('restaurant_timings')
          ->whereRaw('`restaurants`.`id` = `restaurant_timings`.`restaurant_id`')
          ->whereDayOfWeek($currentDateTimeObj->dayOfWeek);
}

function getMeAllClosedRestaurants(&$query)
{
    $from = \func_get_arg(1);
    $to = \func_get_arg(2);
    $current = \func_get_arg(3);
    $currentDateTimeObj = \func_get_arg(4);

    $query->where(function ($subQuery) use ($currentDateTimeObj, $from, $to, $current) {
        mustBeClosedRestaurants($subQuery, $from, $to, $current, $currentDateTimeObj);
    })->orWhereNotExists(function ($subQuery) use ($currentDateTimeObj) {
        mustOpenAtLeastOnceToday($subQuery, $currentDateTimeObj);
    });
}

function orGetMeAllClosedRestaurants(&$query)
{
    $from = \func_get_arg(1);
    $to = \func_get_arg(2);
    $current = \func_get_arg(3);
    $currentDateTimeObj = \func_get_arg(4);

    $query->orWhere(function ($subQuery) use ($currentDateTimeObj, $from, $to, $current) {
        getMeAllClosedRestaurants($subQuery, $from, $to, $current, $currentDateTimeObj);
    });
}

function getMeAllOpenRestaurants(&$query)
{
    $from = \func_get_arg(1);
    $to = \func_get_arg(2);
    $current = \func_get_arg(3);
    $currentDateTimeObj = \func_get_arg(4);

    $query->whereDayOfWeek($currentDateTimeObj->dayOfWeek)
          ->whereRaw("{$from} <= {$current}")
          ->whereRaw("DATE_SUB({$to}, INTERVAL 1 HOUR) > {$current}");
}

function orGetMeAllOpenRestaurants(&$query)
{
    $from = \func_get_arg(1);
    $to = \func_get_arg(2);
    $current = \func_get_arg(3);
    $currentDateTimeObj = \func_get_arg(4);

    $query->orWhere(function ($subQuery) use ($currentDateTimeObj, $from, $to, $current) {
        getMeAllOpenRestaurants($subQuery, $from, $to, $current, $currentDateTimeObj);
    });
}

function getMeAllOpeningSoonRestaurants(&$query)
{
    $from = \func_get_arg(1);
    $to = \func_get_arg(2);
    $current = \func_get_arg(3);
    $currentDateTimeObj = \func_get_arg(4);

    $query->whereDayOfWeek($currentDateTimeObj->dayOfWeek)
        ->whereRaw("{$from} > {$current}")
        ->whereRaw("{$from} <= DATE_ADD({$current}, INTERVAL 1 HOUR)");

    mustNotBeAnOpenRestaurant($query, $from, $to, $current, $currentDateTimeObj);
}

function orGetMeAllOpeningSoonRestaurants(&$query)
{
    $from = \func_get_arg(1);
    $to = \func_get_arg(2);
    $current = \func_get_arg(3);
    $currentDateTimeObj = \func_get_arg(4);

    $query->orWhere(function ($subQuery) use ($currentDateTimeObj, $from, $to, $current) {
        getMeAllOpeningSoonRestaurants($subQuery, $from, $to, $current, $currentDateTimeObj);
    });
}

function getMeAllClosingSoonRestaurants(&$query)
{
    $to = \func_get_arg(2);
    $current = \func_get_arg(3);
    $currentDateTimeObj = \func_get_arg(4);

    $query->whereDayOfWeek($currentDateTimeObj->dayOfWeek)
          ->whereRaw("{$to} > {$current}")
          ->whereRaw("{$to} < DATE_ADD({$current}, INTERVAL 1 HOUR)");
}

function orGetMeAllClosingSoonRestaurants(&$query)
{
    $from = \func_get_arg(1);
    $to = \func_get_arg(2);
    $current = \func_get_arg(3);
    $currentDateTimeObj = \func_get_arg(4);

    $query->orWhere(function ($subQuery) use ($currentDateTimeObj, $from, $to, $current) {
        getMeAllClosingSoonRestaurants($subQuery, $from, $to, $current, $currentDateTimeObj);
    });
}
