<?php

namespace App\Models\Management;

use App\Models\Static\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class School extends Model
{

    protected $connection = 'mysql_portal';
    protected $table = 'schools';


    protected $fillable = [
        'name',
        'persian_name',
        'gender',
        'educational_charter',
        'status',
        'address',
        'address_fa',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function genderInfo(): BelongsTo
    {
        return $this->belongsTo(Gender::class, 'gender', 'id');
    }
}
