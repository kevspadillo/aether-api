<?php

use Illuminate\Database\Seeder;

use App\Models\LoanTypes;

class LoanTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LoanTypes::query()->truncate();
        LoanTypes::create(['loan_type_id' => '1', 'loan_type' => 'Emergency' ]);
        LoanTypes::create(['loan_type_id' => '2', 'loan_type' => 'Regular' ]);
    }
}
