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
        Schema::create('sub_question_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_question_id');
            $table->foreign('sub_question_id')->references('id')->on('sub_questions');
            $table->text('option');
            $table->boolean('correct')->default(false);
            $table->unsignedBigInteger('adder')->nullable();
            $table->unsignedBigInteger('editor')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_question_options');
    }
};
