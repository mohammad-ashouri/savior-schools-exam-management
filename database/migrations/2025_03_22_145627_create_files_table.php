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
        Schema::create('files', function (Blueprint $table) {
//            $table->id();
//            $table->string('title')->nullable();
//            $table->string('type');
//            $table->string('model')->nullable();
//            $table->integer('model_id')->nullable();
//            $table->string('src');
//            $table->boolean('status')->default(true)->after('src');
//            $table->unsignedBigInteger('adder')->nullable();
//            $table->foreign('adder')->references('id')->on('users');
//            $table->unsignedBigInteger('editor')->nullable();
//            $table->foreign('editor')->references('id')->on('users');
//            $table->timestamps();
//            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
