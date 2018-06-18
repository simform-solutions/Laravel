<?php

namespace App\Http\Transformers;

use App\FoodCategory;
use League\Fractal\TransformerAbstract;

class FoodCategoryTransformer extends TransformerAbstract
{
    protected $extra;

    public function __construct(array $extra = [])
    {
        $this->extra = $extra;
    }

    public function transform(FoodCategory $foodCategory)
    {
        return array_merge([
            'id' => $foodCategory->id,
            'name' => $foodCategory->name
        ], $this->extra);
    }
}
