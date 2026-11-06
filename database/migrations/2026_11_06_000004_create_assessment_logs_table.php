<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('assessment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('student_attempts')->cascadeOnDelete();
            $table->char('selected_option', 1)->nullable();
            $table->string('student_formula')->nullable();
            $table->text('ai_detected_misconception')->nullable();
            $table->string('verification_answer')->nullable();
            $table->string('remediation_status')->nullable();
            $table->text('teacher_notes')->nullable();
            $table->timestamp('teacher_approved_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('assessment_logs'); }
};
