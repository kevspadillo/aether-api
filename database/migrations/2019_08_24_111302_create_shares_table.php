<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shares', function (Blueprint $table) {
            $table->bigIncrements('share_id');
            $table->integer('user_id');
            $table->string('reference_code');
            $table->string('number_of_shares');
            $table->string('rate_per_share');
            $table->string('term_id')->nullable();
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
        Schema::dropIfExists('shares');
    }
}
