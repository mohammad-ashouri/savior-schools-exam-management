<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasRoles, Notifiable;

    protected $connection = 'mysql_portal';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'mobile',
        'password',
        'status',
        'adder',
        'editor',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function adderInfo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adder', 'id');
    }

    public function editorInfo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'editor', 'id');
    }

    public function getRolesNamesAttribute(): string
    {
        return implode(',',$this->roles()->pluck('name')->toArray());
    }

    public function generalInformation(): BelongsTo
    {
        return $this->belongsTo(GeneralInformation::class, 'id', 'user_id');
    }
}
