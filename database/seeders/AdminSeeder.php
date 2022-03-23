<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds for administrators in the system.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory(10)->create();

        /**
         * Login Details For Kennedy Glover
         * email: kennedy@glover.co
         * password: password
         */
        $admin_one = $users->find(1);
        $admin_one->first_name = "Kennedy";
        $admin_one->last_name = "Glover";
        $admin_one->email = "kennedy@glover.co";
        $admin_one->password = bcrypt('password');
        $admin_one->save();

        /**
         * Login Details For Patrick Glover
         * email: patrick@glover.co
         * password: password
         */
        $admin_two = $users->find(2);
        $admin_two->first_name = "Patrick";
        $admin_two->last_name = "Glover";
        $admin_two->email = "patrick@glover.co";
        $admin_two->password = bcrypt('password');
        $admin_two->save();

        foreach ($users as $user){
            $user->assignRole('admin');
        };
    }
}
