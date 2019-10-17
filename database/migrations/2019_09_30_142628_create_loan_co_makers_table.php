<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanCoMakersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_co_makers', function (Blueprint $table) {
            $table->bigIncrements('loan_co_maker_id');
            $table->integer('loan_id');
            $table->integer('co_maker_user_id');
            $table->integer('status_id');
            $table->double('freeze_amount', 8, 2);
            $table->timestamp('verified_datetime')->nullable();
            $table->timestamp('updated_datetime')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_co_makers');
    }
}
