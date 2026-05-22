<?php

namespace App\Models\Management;

use App\Models\GeneralInformation;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentApplianceStatus extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql_portal';

    protected $table = 'student_appliance_statuses';

    public function academicYearInfo(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year', 'id');
    }

    public function studentGeneralInfo(): BelongsTo
    {
        return $this->belongsTo(GeneralInformation::class, 'student_id', 'user_id');
    }
}
