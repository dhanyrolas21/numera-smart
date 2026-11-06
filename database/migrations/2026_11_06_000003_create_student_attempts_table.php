<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('current_step')->default(1);
            $table->enum('status', ['in_progress', 'waiting_review', 'completed'])->default('in_progress');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('student_attempts'); }
};
