<?php

use Illuminate\Database\Seeder;

use App\Models\LoanPurposes;

class LoanPurposesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LoanPurposes::query()->truncate();
        LoanPurposes::create(['loan_purpose' => 'Educational', 'loan_type_id' => '1' ]);
        LoanPurposes::create(['loan_purpose' => 'Medical',     'loan_type_id' => '1' ]);
        LoanPurposes::create(['loan_purpose' => 'Personal',    'loan_type_id' => '2' ]);
    }
}
