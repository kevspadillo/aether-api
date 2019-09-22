<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSavingsTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('savings_transactions', function (Blueprint $table) {
            $table->bigIncrements('savings_transaction_id');
            $table->integer('member_id');
            $table->integer('member_transaction_id');
            $table->string('reference_id')->nullable();
            $table->date('transaction_date');
            $table->double('savings_deposit', 8, 2);
            $table->double('interest', 8, 2);
            $table->double('savings', 8, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('savings_transactions');
    }
}
