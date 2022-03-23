<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            User::ROLE_ADMIN,
        ];

        foreach ($roles as $role) {

            Role::create([
                'name' => $role
            ]);
        }
    }
}
