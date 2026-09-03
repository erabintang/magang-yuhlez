<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternshipApplication extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'internship_applications';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'program_id',
        'position_id',
        'intern_id',
        'status',
        'cover_letter',
        'rejection_reason',
        'applied_at',
        'reviewed_at',
        'reviewed_by',
    ];

    protected function casts(): array
    {
        return [
            'applied_at' => 'datetime',
            'reviewed_at' => 'datetime',
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

    public function position(): BelongsTo
    {
        return $this->belongsTo(InternshipPosition::class, 'position_id');
    }

    public function intern(): BelongsTo
    {
        return $this->belongsTo(InternProfile::class, 'intern_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'reviewed_by');
    }

    public function histories()
    {
        return $this->hasMany(ApplicationStatusHistory::class, 'application_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'ACCEPTED');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'REJECTED');
    }
}
