<?php

namespace App\Models\Management;

use App\Models\Management\Course;
use App\Models\Management\Classroom;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassroomCourse extends Model
{
    protected $connection = 'mysql_portal';
    protected $table = "classroom_courses";
    protected $fillable = [
        'id',
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

    public function classroomInfo(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    public function courseInfo(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function teacherInfo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
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
