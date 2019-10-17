<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanPurposesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_purposes', function (Blueprint $table) {
            $table->bigIncrements('loan_purpose_id');
            $table->string('loan_purpose');
            $table->integer('loan_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_purposes');
    }
}
