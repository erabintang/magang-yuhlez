<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyProfile extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'company_profiles';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'slug',
        'name',
        'short_description',
        'description',
        'logo_file_id',
        'whatsapp',
        'contact_email',
        'address',
        'gmap_embed',
        'gmail_access',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'user_id');
    }

    public function logo(): BelongsTo
    {
        return $this->belongsTo(File::class, 'logo_file_id');
    }

    public function programs(): HasMany
    {
        return $this->hasMany(InternshipProgram::class, 'company_id');
    }

    public function works(): HasMany
    {
        return $this->hasMany(Work::class, 'company_id');
    }
}
