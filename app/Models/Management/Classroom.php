<?php

namespace App\Models\Management;

use App\Models\Static\Level;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    protected $connection = 'mysql_portal';
    protected $table = "classrooms";
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

    public function academicYearInfo(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }

    public function gradeInfo(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'grade_id', 'id');
    }

    public function courses(): HasMany
    {
        return $this->HasMany(ClassroomCourse::class);
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
