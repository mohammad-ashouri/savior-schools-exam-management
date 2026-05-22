<?php

namespace App\Models\Management;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentInformation extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql_portal';

    protected $table = 'student_informations';

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
