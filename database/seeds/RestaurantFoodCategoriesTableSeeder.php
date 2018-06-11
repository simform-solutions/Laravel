<?php

use Illuminate\Database\Seeder;

class RestaurantFoodCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $restaurant = \App\Restaurant::wherePhone('+9107940171133')->first();

        $foodCategories = [
            'Punjabi'      => true,
            'Gujarati'     => false,
            'South Indian' => false,
            'Italian'      => false
        ];

        foreach ($foodCategories as $foodCategory => $isSpecial) {
            $restaurant->foodCategories()->attach(\App\FoodCategory::whereName($foodCategory)->first(), ['is_special' => $isSpecial]);
        }
    }
}
