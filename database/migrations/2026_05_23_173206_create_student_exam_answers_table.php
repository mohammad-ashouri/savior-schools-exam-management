<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_exam_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_exam_question_id');
            $table->foreign('student_exam_question_id')->references('id')->on('student_exam_questions')->onDelete('cascade');
            $table->unsignedBigInteger('option_id')->nullable();
            $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');
            $table->unsignedBigInteger('sub_question_id')->nullable();
            $table->foreign('sub_question_id')->references('id')->on('sub_questions')->onDelete('cascade');
            $table->unsignedBigInteger('sub_question_option_id')->nullable();
            $table->foreign('sub_question_option_id')->references('id')->on('sub_question_options')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_exam_answers');
    }
};
