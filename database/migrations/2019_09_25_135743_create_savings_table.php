<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSavingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('savings', function (Blueprint $table) {
            $table->bigIncrements('savings_id');
            $table->integer('user_id');
            $table->double('amount', 8, 2);
            $table->string('amount_in_words')->nullable();
            $table->string('check_number');
            $table->string('representative_name');
            $table->date('payment_date');
            $table->integer('approved_by_id')->nullable();
            $table->integer('declined_by_id')->nullable();
            $table->smallInteger('status_id');
            $table->smallInteger('is_deleted')->default(0);;
            $table->timestamp('created_datetime')->useCurrent();
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
        Schema::dropIfExists('savings');
    }
}
