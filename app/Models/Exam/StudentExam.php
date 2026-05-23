<?php

namespace App\Models\Exam;

use App\Models\Management\ClassroomCourse;
use App\Models\Management\ClassroomStudent;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentExam extends Model
{
    use SoftDeletes;

    protected $table = "student_exams";
    protected $fillable = [
        'id',
        'classroom_student_id',
        'classroom_course_id',
        'term',
    ];

    protected $hidden = [
        'adder',
        'editor',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function classroomStudentInfo(): BelongsTo
    {
        return $this->belongsTo(ClassroomStudent::class, 'classroom_student_id');
    }

    public function classroomCourseInfo(): BelongsTo
    {
        return $this->belongsTo(ClassroomCourse::class, 'classroom_course_id');
    }

    public function questions()
    {
        return $this->hasMany(StudentExamQuestion::class, 'student_exam_id');
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
