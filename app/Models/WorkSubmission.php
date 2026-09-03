<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkSubmission extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'work_submissions';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'work_id',
        'intern_id',
        'title',
        'description',
        'tech_stack',
        'status',
        'review_note',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class, 'work_id');
    }

    public function intern(): BelongsTo
    {
        return $this->belongsTo(InternProfile::class, 'intern_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'reviewed_by');
    }

    public function files(): HasMany
    {
        return $this->hasMany(WorkSubmissionFile::class, 'submission_id');
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

    // Constants
    const STATUS_PENDING = 'PENDING';
    const STATUS_ACCEPTED = 'ACCEPTED';
    const STATUS_REJECTED = 'REJECTED';
}
