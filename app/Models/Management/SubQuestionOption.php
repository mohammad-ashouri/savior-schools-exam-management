<?php

namespace App\Models\Management;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubQuestionOption extends Model
{
    use SoftDeletes;
    protected $table = "sub_question_options";
    protected $fillable = [
        'id',
        'sub_question_id',
        'option',
        'correct',
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

    public function subQuestionInfo(): BelongsTo
    {
        return $this->belongsTo(SubQuestion::class, 'sub_question_id');
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
