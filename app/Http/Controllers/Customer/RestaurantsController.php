<?php

namespace App\Http\Controllers\Customer;

use App\Http\Requests\Customer\RestaurantsList;
use App\Http\Transformers\RestaurantTransformer;
use App\Restaurant;
use Carbon\Carbon;
use EllipseSynergie\ApiResponse\Laravel\Response;
use App\Http\Controllers\Controller;
use Grimzy\LaravelMysqlSpatial\Types\Point;

class RestaurantsController extends Controller
{
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function getList(RestaurantsList $request)
    {
        $nearByRestaurants = Restaurant::distance(
            'location',
            new Point($request->get('latitude'), $request->get('longitude')),
            $request->get('radius') ?: env('NEAR_BY_RADIUS_IN_KM')
        )->whereHas('timings', function ($tq) use ($request) {
            if ($request->request->has('hours_of_operations') && count($request->get('hours_of_operations')) > 0) {
                $currentDateTimeObj = Carbon::now();

                $from = "STR_TO_DATE(concat('{$currentDateTimeObj->toDateString()}', ' ', from_time), \"%Y-%m-%d %H:%i:%s\")";
                $to = "STR_TO_DATE(concat('{$currentDateTimeObj->toDateString()}', ' ', to_time), \"%Y-%m-%d %H:%i:%s\")";
                $current = "STR_TO_DATE('{$currentDateTimeObj->toDateTimeString()}', \"%Y-%m-%d %H:%i:%s\")";

                $tq->where(function ($mtq) use ($request, $currentDateTimeObj, $from, $to, $current) {
                    foreach ($request->get('hours_of_operations') as $hourOfOperation) {
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
        })->whereHas('foodCategories', function ($fq) use ($request) {
            if ($request->request->has('food_categories') && count($request->get('food_categories')) > 0) {
                $fq->whereIn('food_categories.id', $request->get('food_categories'));
            }
        })->with(['foodCategories' => function ($fq) {
            return $fq->orderBy('is_special', 'DESC');
        }])->whereIsActive(true)->with('photos');

        if ($request->request->has('cost_of_food') && count($request->get('cost_of_food')) > 0) {
            $nearByRestaurants->whereIn('price_range', $request->get('cost_of_food'));
        }

        if ($search = $request->get('search')) {
            $nearByRestaurants->search($search);
        }

        return $this->response->withPaginator(
            $nearByRestaurants->paginate(round($request->get('perPage')) ?: env('DEFAULT_PER_PAGE_RECORDS')),
            new RestaurantTransformer
        );
    }
}
