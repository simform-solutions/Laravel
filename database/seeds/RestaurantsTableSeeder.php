<?php

use Illuminate\Database\Seeder;
use Grimzy\LaravelMysqlSpatial\Types\Point;

class RestaurantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $restaurant = new \App\Restaurant([
            'name' => 'Tinello',
            'address' => 'Hyatt Regency Ahmedabad,17/A, Ashram Road, India, Lobby Level, Ahmedabad, Gujarat 380014',
            'phone' => '+9107940171133',
            'avg_ratings' => 4.3,
            'photo' => 'https://img1.nbstatic.in/la-webp-l/58aacbeecff47e000d4abdfd.jpg',
            'location' => new Point(23.043539, 72.570439),
            'description' => 'Standard hotel\'s dining room for a mix of popular Indian and Italian dishes.',
            'price_range' => 2,
            'time_zone' => '+05:30'
        ]);
        $restaurant->manager()->associate(\App\User::where('email', '=', 'manager@captain.com')->first());
        $restaurant->save();
    }
}
