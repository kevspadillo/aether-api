<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_history', function (Blueprint $table) {
            $table->bigIncrements('loan_history_id');
            $table->integer('user_id');
            $table->integer('loan_id');
            $table->string('history_title');
            $table->string('history_note');
            $table->timestamp('created_datetime')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_history');
    }
}
