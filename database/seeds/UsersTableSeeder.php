<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Models\UserType;
use App\Models\UserStatus;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->truncate();
        User::create([
            'firstname'        => 'Kevin',
            'middlename'       => 'Alverio',
            'lastname'         => 'Padilla',
            'gender'           => 'm',
            'civil_status'     => 's',
            'tin_number'       => '1234567890',
            'sss_number'       => '1234567987',
            'email'            => 'kevin.padilla0717@gmail.com',
            'contact_number'   => '09157544558',
            'password'         => Hash::make('superadmin'),
            'user_status_id'        => UserStatus::ACTIVE,
            'user_type_id'     => UserType::ADMIN,
            'created_datetime' => date('Y-m-d H:i:s'),
            'updated_datetime' => date('Y-m-d H:i:s')
        ]);
    }
}
