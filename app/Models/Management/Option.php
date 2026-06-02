<?php

namespace App\Models\Management;

use App\Models\User;
use App\Service\LogService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Model
{
    use SoftDeletes;
    protected $table = "options";
    protected $fillable = [
        'id',
        'question_id',
        'option',
        'correct',
        'adder',
        'editor',
    ];

    protected $hidden = [
        'correct',
        'adder',
        'editor',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected static function booted(): void
    {
        static::created(function ($model) {
            LogService::log('options', [
                'job' => 'option created',
                'value' => $model->toArray(),
            ]);
        });
        static::updated(function ($model) {
            LogService::log('options', [
                'job' => 'option updated',
                'old value' => $model->getOriginal(),
                'new value' => $model->getDirty(),
            ]);
        });
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
