<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dateTime = date('Y-m-d H:i:s');

        DB::table('roles')->insert([
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'created_at' => $dateTime,
                'updated_at' => $dateTime
            ],
            [
                'name' => 'restaurant_manager',
                'display_name' => 'Restaurant Manager',
                'created_at' => $dateTime,
                'updated_at' => $dateTime
            ],
            [
                'name' => 'waiter',
                'display_name' => 'Waiter',
                'created_at' => $dateTime,
                'updated_at' => $dateTime
            ],
            [
                'name' => 'customer',
                'display_name' => 'Customer',
                'created_at' => $dateTime,
                'updated_at' => $dateTime
            ]
        ]);
    }
}
