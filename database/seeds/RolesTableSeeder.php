<?php

use Illuminate\Database\Seeder;

use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::query()->truncate();
        Role::create(['role_id' => '1',  'title' => 'Administrator', 'is_core_role' => '1' ]);
        Role::create(['role_id' => '2',  'title' => 'Manager',       'is_core_role' => '0' ]);
        Role::create(['role_id' => '3',  'title' => 'Guest',         'is_core_role' => '0' ]);
        Role::create(['role_id' => '4',  'title' => 'Member',        'is_core_role' => '0' ]);
    }
}
