<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawalHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawal_history', function (Blueprint $table) {
            $table->bigIncrements('withdrawal_history_id');
            $table->integer('user_id');
            $table->integer('withdrawal_id');
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
        Schema::dropIfExists('withdrawal_history');
    }
}
