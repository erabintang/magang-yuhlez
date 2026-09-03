<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'certificates';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'program_id',
        'intern_id',
        'file_id',
        'certificate_number',
        'issued_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
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

    public function intern(): BelongsTo
    {
        return $this->belongsTo(InternProfile::class, 'intern_id');
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    // Constants
    const STATUS_NOT_ELIGIBLE = 'NOT_ELIGIBLE';
    const STATUS_ELIGIBLE = 'ELIGIBLE';
    const STATUS_ISSUED = 'ISSUED';
}
