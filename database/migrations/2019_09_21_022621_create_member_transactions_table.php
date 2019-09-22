<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_transactions', function (Blueprint $table) {
            $table->bigIncrements('member_transaction_id');
            $table->smallInteger('is_posted');
            $table->string('transaction_type');
            $table->integer('user_id');
            $table->integer('posted_by_user_id')->nullable();
            $table->timestamp('created_datetime')->useCurrent();
            $table->dateTime('posted_datetime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_transactions');
    }
}
