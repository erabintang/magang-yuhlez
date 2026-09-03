<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternshipPosition extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'internship_positions';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'program_id',
        'name',
        'description',
        'quota',
    ];

    protected function casts(): array
    {
        return [
            'quota' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function program(): BelongsTo
    {
        return $this->belongsTo(InternshipProgram::class, 'program_id');
    }

    public function applications()
    {
        return $this->hasMany(InternshipApplication::class, 'position_id');
    }

    // Helper
    public function acceptedCount(): int
    {
        return $this->applications()
            ->where('status', 'ACCEPTED')
            ->whereNull('deleted_at')
            ->count();
    }

    public function isQuotaFull(): bool
    {
        if ($this->quota === null) {
            return false;
        }
        return $this->acceptedCount() >= $this->quota;
    }
}
