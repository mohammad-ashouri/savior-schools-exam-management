<?php

namespace App\Models\Management;

use App\Models\Management\Classroom;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassroomStudent extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql_portal';

    protected $table = 'classroom_students';

    public function classroomInfo(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    public function applianceInfo(): BelongsTo
    {
        return $this->belongsTo(StudentApplianceStatus::class, 'appliance_id');
    }
}
