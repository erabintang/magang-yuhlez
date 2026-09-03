<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkSubmissionFile extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'work_submission_files';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'submission_id',
        'file_id',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    // Relationships
    public function submission(): BelongsTo
    {
        return $this->belongsTo(WorkSubmission::class, 'submission_id');
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id');
    }
}
