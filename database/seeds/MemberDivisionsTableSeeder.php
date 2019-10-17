<?php

use Illuminate\Database\Seeder;
use App\Models\MemberDivision;

class MemberDivisionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MemberDivision::query()->truncate();
        MemberDivision::create(['division_id' => '1', 'division' => 'Primary School' ]);
        MemberDivision::create(['division_id' => '2', 'division' => 'Junior High School' ]);
        MemberDivision::create(['division_id' => '3', 'division' => 'Senior High School' ]);
        MemberDivision::create(['division_id' => '4', 'division' => 'College' ]);
        MemberDivision::create(['division_id' => '5', 'division' => 'Non-Teaching' ]);
    }
}
