<?php

namespace App\Models\Exam;

use App\Models\Management\Question;
use App\Models\User;
use App\Service\LogService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentExamQuestion extends Model
{
    use SoftDeletes;

    protected $table = "student_exam_questions";
    protected $fillable = [
        'id',
        'student_exam_id',
        'question_id',
    ];

    protected $hidden = [
        'adder',
        'editor',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected static function booted(): void
    {
        static::created(function ($model) {
            LogService::log('student exam questions', [
                'job' => 'student exam question created',
                'value' => $model->toArray(),
            ]);
        });
        static::updated(function ($model) {
            LogService::log('student exam questions', [
                'job' => 'student exam question updated',
                'old value' => $model->getOriginal(),
                'new value' => $model->getDirty(),
            ]);
        });
    }

    public function studentExamInfo(): BelongsTo
    {
        return $this->belongsTo(StudentExam::class, 'student_exam_id');
    }

    public function questionInfo(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function adderInfo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adder');
    }

    public function editorInfo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'editor');
    }
}
