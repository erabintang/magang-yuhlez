<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramIntern extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'program_interns';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'program_id',
        'intern_id',
        'application_id',
        'joined_at',
        'removed_at',
    ];

    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
            'removed_at' => 'datetime',
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

    public function application(): BelongsTo
    {
        return $this->belongsTo(InternshipApplication::class, 'application_id');
    }
}
