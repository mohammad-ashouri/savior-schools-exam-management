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
        // Indexes for exam_info table
        Schema::table('exam_info', function (Blueprint $table) {
            $table->index(['classroom_course_id', 'term', 'type']);
        });

        // Indexes for student_exams table
        Schema::table('student_exams', function (Blueprint $table) {
            $table->index(['classroom_course_id', 'classroom_student_id', 'term']);
            $table->index(['classroom_student_id', 'classroom_course_id', 'term']);
        });

        // Indexes for student_exam_questions table
        Schema::table('student_exam_questions', function (Blueprint $table) {
            $table->index(['student_exam_id', 'question_id']);
        });

        // Indexes for student_exam_answers table
        Schema::table('student_exam_answers', function (Blueprint $table) {
            $table->index('student_exam_question_id');
            $table->index(['student_exam_question_id', 'sub_question_id']);
        });

        // Indexes for questions table
        Schema::table('questions', function (Blueprint $table) {
            $table->index(['classroom_course_id', 'term']);
        });

        // Indexes for options table
        Schema::table('options', function (Blueprint $table) {
            $table->index(['question_id', 'correct']);
        });

        // Indexes for sub_questions table
        Schema::table('sub_questions', function (Blueprint $table) {
            $table->index('question_id');
        });

        // Indexes for sub_question_options table
        Schema::table('sub_question_options', function (Blueprint $table) {
            $table->index(['sub_question_id', 'correct']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_info', function (Blueprint $table) {
            $table->dropIndex(['classroom_course_id', 'term', 'type']);
        });

        Schema::table('student_exams', function (Blueprint $table) {
            $table->dropIndex(['classroom_course_id', 'classroom_student_id', 'term']);
            $table->dropIndex(['classroom_student_id', 'classroom_course_id', 'term']);
        });

        Schema::table('student_exam_questions', function (Blueprint $table) {
            $table->dropIndex(['student_exam_id', 'question_id']);
        });

        Schema::table('student_exam_answers', function (Blueprint $table) {
            $table->dropIndex('student_exam_question_id');
            $table->dropIndex(['student_exam_question_id', 'sub_question_id']);
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex(['classroom_course_id', 'term']);
        });

        Schema::table('options', function (Blueprint $table) {
            $table->dropIndex(['question_id', 'correct']);
        });

        Schema::table('sub_questions', function (Blueprint $table) {
            $table->dropIndex('question_id');
        });

        Schema::table('sub_question_options', function (Blueprint $table) {
            $table->dropIndex(['sub_question_id', 'correct']);
        });
    }
};
