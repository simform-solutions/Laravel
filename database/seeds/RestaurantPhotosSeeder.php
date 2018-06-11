<?php

use Illuminate\Database\Seeder;

class RestaurantPhotosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $restaurant = \App\Restaurant::wherePhone('+9107940171133')->first();

        $restaurantPhotos = [
            'https://img1.nbstatic.in/la-webp-l/59a6abdd4cedfd000cea3d65.jpg',
            'https://img1.nbstatic.in/la-webp-l/58aacbea46e0fb000ebacd44.jpg',
            'https://img1.nbstatic.in/la-webp-l/58aacbeecff47e000d4abdfd.jpg',
            'https://media-cdn.tripadvisor.com/media/photo-s/11/95/67/42/tinello.jpg',
            'https://media-cdn.tripadvisor.com/media/photo-s/11/95/67/2a/tinello.jpg',
            'https://media-cdn.tripadvisor.com/media/photo-s/11/61/2b/e3/photo0jpg.jpg',
            'https://media-cdn.tripadvisor.com/media/photo-s/10/e8/e3/65/photo0jpg.jpg',
            'https://media-cdn.tripadvisor.com/media/photo-s/10/13/58/d0/deserts-for-days.jpg',
            'https://media-cdn.tripadvisor.com/media/photo-s/10/13/58/b6/fresh-vegetables-from.jpg',
            'https://media-cdn.tripadvisor.com/media/photo-s/10/13/58/44/rich-chocolate-cake.jpg'
        ];

        foreach ($restaurantPhotos as $photo) {
            $restaurantPhoto = new \App\RestaurantPhoto([
                'photo' => $photo
            ]);
            $restaurantPhoto->restaurant()->associate($restaurant);
            $restaurantPhoto->save();
        }
    }
}
