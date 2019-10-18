<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->bigIncrements('loan_id');
            $table->integer('user_id');
            $table->double('loan_amount', 8, 2);
            $table->date('initial_payment_due_date');
            $table->integer('payment_method_id');
            $table->integer('verified_by')->nullable();
            $table->smallInteger('status_id');
            $table->smallInteger('loan_type_id');
            $table->smallInteger('loan_purpose_id');
            $table->string('collateral')->nullable();
            $table->timestamp('verified_datetime')->nullable();
            $table->timestamp('create_datetime')->useCurrent();
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
        Schema::dropIfExists('loans');
    }
}
