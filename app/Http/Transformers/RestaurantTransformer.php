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
        return array_merge([
            'id' => $restaurant->id,
            'name' => $restaurant->name,
            'address' => $restaurant->address,
            ''
        ], $this->extra);
    }
}
