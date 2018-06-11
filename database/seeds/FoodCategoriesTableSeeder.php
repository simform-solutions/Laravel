<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoodCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dateTime = date('Y-m-d H:i:s');

        DB::table('food_categories')->insert([
            [
                'name' => 'Punjabi',
                'created_at' => $dateTime,
                'updated_at' => $dateTime
            ],
            [
                'name' => 'Gujarati',
                'created_at' => $dateTime,
                'updated_at' => $dateTime
            ],
            [
                'name' => 'Chinese',
                'created_at' => $dateTime,
                'updated_at' => $dateTime
            ],
            [
                'name' => 'South Indian',
                'created_at' => $dateTime,
                'updated_at' => $dateTime
            ],
            [
                'name' => 'Mexican',
                'created_at' => $dateTime,
                'updated_at' => $dateTime
            ],
            [
                'name' => 'Italian',
                'created_at' => $dateTime,
                'updated_at' => $dateTime
            ]
        ]);
    }
}
