<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InternshipProgram extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'internship_programs';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'company_id',
        'slug',
        'title',
        'short_description',
        'description',
        'registration_start',
        'registration_end',
        'program_start',
        'program_end',
    ];

    protected function casts(): array
    {
        return [
            'registration_start' => 'datetime',
            'registration_end' => 'datetime',
            'program_start' => 'datetime',
            'program_end' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function positions(): HasMany
    {
        return $this->hasMany(InternshipPosition::class, 'program_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(InternshipApplication::class, 'program_id');
    }

    public function programInterns(): HasMany
    {
        return $this->hasMany(ProgramIntern::class, 'program_id');
    }

    public function banners(): HasMany
    {
        return $this->hasMany(ProgramBanner::class, 'program_id');
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'program_id');
    }

    // Helper methods
    public function isRegistrationOpen(): bool
    {
        $now = now();
        return $now->between($this->registration_start, $this->registration_end);
    }

    public function isActive(): bool
    {
        $now = now();
        return $now->between($this->program_start, $this->program_end);
    }
}
