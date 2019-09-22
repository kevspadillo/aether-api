<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShareTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('share_transactions', function (Blueprint $table) {
            $table->bigIncrements('share_transaction_id');
            $table->integer('member_id');
            $table->string('reference_id')->nullable();
            $table->date('transaction_date');
            $table->integer('member_transaction_id');
            $table->double('share_capital', 8, 2);
            $table->double('share', 8, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('share_transactions');
    }
}
