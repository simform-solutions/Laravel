<?php

namespace App\Http\Transformers;

use App\Restaurant;
use League\Fractal\TransformerAbstract;

class RestaurantTransformer extends TransformerAbstract
{
    protected $extra;

    public function __construct(array $extra = [])
    {
        $this->extra = $extra;
    }

    public function transform(Restaurant $restaurant)
    {
        $attributes = [
            'id',
            'name',
            'address',
            'avg_ratings',
            'photo',
            'price_range',
            'distance',
            'description',
            'location',
            'time_zone',
            'current_status'
        ];
        !$restaurant->phone || $attributes[] = 'phone';
        !$restaurant->email || $attributes[] = 'email';
        !$restaurant->avg_ratings || $attributes[] = 'avg_ratings';

        $attributes[] = 'foodCategories';
        $attributes[] = 'timings';
        $attributes[] = 'photos';

        return array_merge($restaurant->only($attributes), $this->extra);
    }
}
