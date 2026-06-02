<?php

namespace App\Models\Management;

use App\Models\User;
use App\Service\LogService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubQuestion extends Model
{
    use SoftDeletes;
    protected $table = "sub_questions";
    protected $fillable = [
        'id',
        'question_id',
        'question_type',
        'title',
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

    protected static function booted(): void
    {
        static::created(function ($model) {
            LogService::log('sub questions', [
                'job' => 'sub question created',
                'value' => $model->toArray(),
            ]);
        });
        static::updated(function ($model) {
            LogService::log('sub questions', [
                'job' => 'sub question updated',
                'old value' => $model->getOriginal(),
                'new value' => $model->getDirty(),
            ]);
        });
    }

    public function options()
    {
        return $this->hasMany(SubQuestionOption::class, 'sub_question_id');
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
