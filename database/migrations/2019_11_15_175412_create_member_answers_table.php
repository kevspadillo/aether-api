<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_answers', function (Blueprint $table) {
            $table->bigIncrements('member_answer_id');
            $table->integer('assesment_question_id');
            $table->integer('user_id');
            $table->integer('assessment_question_choice_id');
            $table->string('choice_name');
            $table->integer('is_correct');
            $table->timestamp('datetime_created')->useCurrent();
            $table->timestamp('datetime_updated')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_answers');
    }
}
