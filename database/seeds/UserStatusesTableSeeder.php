<?php

use Illuminate\Database\Seeder;

use App\Models\UserStatus;
class UserStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserStatus::query()->truncate();
        UserStatus::create(['user_status' => 'Pending',  'is_active' => '1' ]);
        UserStatus::create(['user_status' => 'Verified', 'is_active' => '1' ]);
        UserStatus::create(['user_status' => 'Approved', 'is_active' => '1' ]);
        UserStatus::create(['user_status' => 'Rejected', 'is_active' => '1' ]);
        UserStatus::create(['user_status' => 'Active',   'is_active' => '1' ]);
        UserStatus::create(['user_status' => 'Inactive', 'is_active' => '1' ]);
    }
}
