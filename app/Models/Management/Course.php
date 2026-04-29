<?php

namespace App\Models\Management;

use App\Models\Static\Gender;
use App\Models\Static\Level;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    protected $connection = 'mysql_portal';
    protected $table = "courses";
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

    public function gradeInfo(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'grade');
    }

    public function genderInfo(): BelongsTo
    {
        return $this->belongsTo(Gender::class, 'gender');
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
