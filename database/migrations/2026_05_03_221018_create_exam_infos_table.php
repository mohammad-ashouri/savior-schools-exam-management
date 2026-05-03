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
        Schema::create('exam_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('classroom_course_id');
            $table->enum('term', ['first', 'second', 'retake']);
            $table->string('type');
            $table->string('value');
            $table->unsignedBigInteger('user');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_info');
    }
};
