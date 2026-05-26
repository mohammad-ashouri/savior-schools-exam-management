<?php

namespace App\Models\Exam;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentExamAnswer extends Model
{
    use SoftDeletes;
    protected $table = "student_exam_answers";
    protected $fillable = [
        'id',
        'student_exam_question_id',
        'option_id',
        'sub_question_id',
        'sub_question_option_id',
        'user_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function studentExamQuestionInfo(): BelongsTo
    {
        return $this->belongsTo(StudentExamQuestion::class, 'student_exam_question_id');
    }
}
