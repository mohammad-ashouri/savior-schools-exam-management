<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('student_exams', function (Blueprint $table) {
            $table->timestamp('finished_at')->nullable();
        });

        $exams = \App\Models\Exam\StudentExam::get();
        foreach ($exams as $exam) {
            $exam->finished_at = $exam->updated_at;
            $exam->save();
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_exams', function (Blueprint $table) {
            $table->dropColumn('finished_at');
        });
    }
};
