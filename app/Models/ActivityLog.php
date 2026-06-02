<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $connection = 'logs';
    protected $table = 'exam_activity_logs';

    protected $fillable = [
        'activity_type',
        'appliance_id',
        'user_id',
        'activity',
        'ip_address',
        'device',
        'user_agent',
        'platform',
        'platform_version',
        'browser',
        'browser_version',
        'device_type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
