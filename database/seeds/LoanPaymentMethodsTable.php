<?php

use Illuminate\Database\Seeder;

use App\Models\LoanPaymentMethods;

class LoanPaymentMethodsTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LoanPaymentMethods::query()->truncate();
        LoanPaymentMethods::create(['payment_method' => 'Bi-monthly']);
        LoanPaymentMethods::create(['payment_method' => 'Monthly']);
    }
}
