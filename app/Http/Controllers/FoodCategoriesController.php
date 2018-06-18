<?php

namespace App\Http\Controllers;

use App\FoodCategory;
use App\Http\Transformers\FoodCategoryTransformer;
use EllipseSynergie\ApiResponse\Laravel\Response;

class FoodCategoriesController extends Controller
{
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function getList()
    {
        return $this->response->withCollection(FoodCategory::orderBy('name')->get(), new FoodCategoryTransformer);
    }
}
