<?php

namespace App\Models\Management;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    protected $table = "questions";
    protected $fillable = [
        'id',
        'classroom_course_id',
        'question_type',
        'title',
        'image',
        'order',
        'term',
        'adder',
        'editor',
    ];

    protected $hidden = [
        'adder',
        'editor',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function classroomCourseInfo(): BelongsTo
    {
        return $this->belongsTo(ClassroomCourse::class, 'classroom_course_id');
    }

    public function options()
    {
        return $this->hasMany(Option::class, 'question_id');
    }

    public function subQuestions()
    {
        return $this->hasMany(SubQuestion::class, 'question_id');
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
