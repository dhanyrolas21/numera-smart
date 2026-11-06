<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentLog extends Model
{
    protected $fillable = [
        'attempt_id', 'selected_option', 'student_formula', 'ai_detected_misconception',
        'verification_answer', 'remediation_status', 'teacher_notes', 'teacher_approved_at',
    ];
    protected $casts = ['teacher_approved_at' => 'datetime'];

    public function attempt() { return $this->belongsTo(StudentAttempt::class, 'attempt_id'); }
}
