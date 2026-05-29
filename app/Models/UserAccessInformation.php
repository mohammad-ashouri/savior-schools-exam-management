<?php

namespace App\Models;

use App\Services\LogService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAccessInformation extends Model
{
    protected $table = 'user_access_informations';

    protected $fillable = [
        'user_id',
        'principal',
        'admissions_officer',
        'financial_manager',
        'interviewer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function userInfo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user', 'id');
    }
}
