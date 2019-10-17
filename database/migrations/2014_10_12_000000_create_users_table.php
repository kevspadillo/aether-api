<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('firstname');
            $table->string('middlename');
            $table->string('lastname');
            $table->string('gender')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('tin_number')->nullable();
            $table->string('sss_number')->nullable();
            $table->string('email', 100)->unique();
            $table->date('birth_date')->nullable();
            $table->string('mailing_address')->nullable();
            $table->string('nationality')->nullable();
            $table->string('landline_number')->nullable();
            $table->string('mobile_number')->nullable();
            $table->integer('employee_type_id')->nullable();
            $table->integer('division_id')->nullable();
            $table->string('other_income_source')->nullable();
            $table->string('password', 100);
            $table->smallInteger('user_status_id');
            $table->smallInteger('role_id');
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
        Schema::dropIfExists('users');
    }
}
