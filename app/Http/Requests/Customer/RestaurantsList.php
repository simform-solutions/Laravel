<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class RestaurantsList extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'perPage' => 'required|integer|min:1',
            'page' => 'required|integer|min:1',
            'radius' => 'required|integer|min:1',
            'cost_of_food' => 'array|max:3',
            'cost_of_food.*' => 'in:1,2,3|distinct',
            'food_categories' => 'array',
            'food_categories.*' => 'exists:food_categories,id',
            'hours_of_operations' => 'array|max:4',
            'hours_of_operations.*' => 'in:1,2,3,4|distinct',
            'search' => 'string'
        ];
    }
}
