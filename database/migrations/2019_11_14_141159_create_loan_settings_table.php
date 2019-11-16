<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_settings', function (Blueprint $table) {
            $table->bigIncrements('loan_setting_id');
            $table->integer('user_id');
            $table->double('interest_rate', 8, 2);
            $table->smallInteger('is_active')->default(0);
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
        Schema::dropIfExists('loan_settings');
    }
}
