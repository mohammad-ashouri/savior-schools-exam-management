<?php

namespace App\Models\Management;

use App\Models\Lms\GeneralBehaviorQuestion;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralBehavior extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql_portal';

    protected $table = 'general_behaviors';

    protected $fillable = [
        'classroom_id',
        'appliance_id',
        'question_id',
        'further_details_description',
        'grade',
        'status',
        'adder',
        'editor',
    ];

    public function adderInfo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adder');
    }

    public function editorInfo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'editor');
    }
}
