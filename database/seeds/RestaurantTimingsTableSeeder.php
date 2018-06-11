<?php

use Illuminate\Database\Seeder;

class RestaurantTimingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $restaurant = \App\Restaurant::wherePhone('+9107940171133')->first();

        $timings = [
            0 => [
                'from_time' => '10:00:00',
                'to_time'   => '22:00:00'
            ],
            1 => [
                'from_time' => '09:00:00',
                'to_time'   => '20:00:00'
            ],
            3 => [
                [
                    'from_time' => '08:00:00',
                    'to_time'   => '12:00:00'
                ],
                [
                    'from_time' => '15:00:00',
                    'to_time'   => '21:00:00'
                ]
            ],
            5 => [
                [
                    'from_time' => '07:00:00',
                    'to_time'   => '11:00:00'
                ],
                [
                    'from_time' => '14:00:00',
                    'to_time'   => '18:00:00'
                ],
                [
                    'from_time' => '20:00:00',
                    'to_time'   => '23:00:00'
                ]
            ],
            6 => [
                'from_time' => '15:00:00',
                'to_time'   => '24:00:00'
            ],
        ];

        $insertTimings = function ($timing) use ($restaurant) {
            $restaurantTiming = new \App\RestaurantTiming($timing);
            $restaurantTiming->restaurant()->associate($restaurant);
            $restaurantTiming->save();
        };

        foreach ($timings as $weekday => $timing) {
            if (array_key_exists('from_time', $timing)) {
                $timing['day_of_week'] = $weekday;
                $insertTimings($timing);
            } else {
                foreach ($timing as $timeSlot) {
                    $timeSlot['day_of_week'] = $weekday;
                    $insertTimings($timeSlot);
                }
            }
        }
    }
}
