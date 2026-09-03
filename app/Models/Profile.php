<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Notifications\Notifiable;

class Profile extends Authenticatable
{
    use HasFactory, HasUuids, SoftDeletes, Notifiable;

    protected $table = 'profiles';
    protected $keyType = 'string';
    public $incrementing = false;

    // Disable remember_token - database profiles table doesn't have this column
    protected $rememberTokenName = '';

    protected $fillable = [
        'id',
        'name',
        'email',
        'role',
        'category',
        'password_hash',
    ];

    protected $hidden = [
        'password_hash',
    ];

    public function getAuthPassword(): string
    {
        return $this->password_hash ?? '';
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function companyProfile(): HasOne
    {
        return $this->hasOne(CompanyProfile::class, 'user_id');
    }

    public function internProfile(): HasOne
    {
        return $this->hasOne(InternProfile::class, 'user_id');
    }

    // creatorProfile() removed per spec - only ROOT, COMPANY, INTERN roles exist.

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    // Helper methods
    public function isRoot(): bool
    {
        return $this->role === 'ROOT';
    }

    public function isCompany(): bool
    {
        return $this->role === 'COMPANY';
    }

    public function isIntern(): bool
    {
        return $this->role === 'INTERN';
    }

}
