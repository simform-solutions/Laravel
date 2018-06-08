<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userDetails = [
            'admin' => [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@captain.com',
                'password' => bcrypt('Admin.321')
            ],
            'restaurant_manager' => [
                'first_name' => 'Manager',
                'last_name' => 'User',
                'email' => 'manager@captain.com',
                'password' => bcrypt('Manager.321')
            ]
        ];

        foreach ($userDetails as $role => $userDetail) {
            $user = new \App\User($userDetail);
            $user->save();
            $user->attachRole(\App\Role::where('name', '=', $role)->first());
        }
    }
}
