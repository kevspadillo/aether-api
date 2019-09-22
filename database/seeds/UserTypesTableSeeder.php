<?php

use Illuminate\Database\Seeder;

use App\Models\UserType;
class UserTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserType::query()->truncate();
        UserType::create(['user_type' => 'Client', 'is_active' => '1' ]);
        UserType::create(['user_type' => 'Guest',  'is_active' => '1' ]);
        UserType::create(['user_type' => 'Admin',  'is_active' => '1' ]);
    }
}
