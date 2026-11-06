<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['dunia', 'context_text', 'options', 'correct_option', 'correct_value'];
    protected $casts = ['options' => 'array'];

    public function attempts() { return $this->hasMany(StudentAttempt::class); }
}
