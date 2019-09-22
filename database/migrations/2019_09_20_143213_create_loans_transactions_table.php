<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_transactions', function (Blueprint $table) {
            $table->bigIncrements('loan_transaction_id');
            $table->integer('member_id');
            $table->integer('member_transaction_id');
            $table->string('reference_id')->nullable();
            $table->date('transaction_date');
            $table->double('loans_receivable', 8, 2)->nullable();
            $table->double('interest_on_loan', 8, 2)->nullable();
            $table->double('penalty', 8, 2)->nullable();
            $table->double('remaining_loan', 8, 2)->nullable();
            $table->double('total_interest', 8, 2)->nullable();
            $table->double('total_penalty', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_transactions');
    }
}
