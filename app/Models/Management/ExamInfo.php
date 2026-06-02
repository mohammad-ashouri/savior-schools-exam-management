<?php

namespace App\Models\Management;

use App\Models\User;
use App\Service\LogService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamInfo extends Model
{
    use SoftDeletes;
    protected $connection = "pgsql";
    protected $table = "exam_info";
    protected $fillable = [
        'id',
        'classroom_course_id',
        'term',
        'type',
        'value',
        'user'
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
            LogService::log('exam info', [
                'job' => 'exam info created',
                'value' => $model->toArray(),
            ]);
        });
        static::updated(function ($model) {
            LogService::log('exam info', [
                'job' => 'exam info updated',
                'old value' => $model->getOriginal(),
                'new value' => $model->getDirty(),
            ]);
        });
    }

    public function userInfo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user');
    }
}
