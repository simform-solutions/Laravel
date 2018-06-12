<?php

namespace App\Http\Controllers\Customer;

use App\Http\Requests\Customer\RestaurantsList;
use App\Restaurant;
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
        //dd(Restaurant::distance('location', new Point($request->get('latitude'), $request->get('longitude')), 5)->get());
    }
}
