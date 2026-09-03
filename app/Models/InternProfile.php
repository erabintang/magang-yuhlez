<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternProfile extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'intern_profiles';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'slug',
        'name',
        'short_description',
        'description',
        'profile_photo_file_id',
        'whatsapp',
        'contact_email',
        'address',
        'gmail_access',
        'cv_file_id',
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

    public function photo(): BelongsTo
    {
        return $this->belongsTo(File::class, 'profile_photo_file_id');
    }

    public function cv(): BelongsTo
    {
        return $this->belongsTo(File::class, 'cv_file_id');
    }

    public function applications()
    {
        return $this->hasMany(InternshipApplication::class, 'intern_id');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'intern_id');
    }

    public function works()
    {
        return $this->belongsToMany(Work::class, 'work_interns', 'intern_id', 'work_id')
            ->withPivot('added_at', 'removed_at');
    }

    public function programInterns()
    {
        return $this->hasMany(ProgramIntern::class, 'intern_id');
    }

    public function submissions()
    {
        return $this->hasMany(WorkSubmission::class, 'intern_id');
    }

    // Profile Completion
    public array $requiredFields = [
        'name' => 'Nama Lengkap',
        'short_description' => 'Deskripsi Singkat',
        'whatsapp' => 'No. WhatsApp',
        'contact_email' => 'Email Kontak',
        'address' => 'Alamat',
        'gmail_access' => 'Gmail Access',
        'cv_file_id' => 'CV / Resume',
        'profile_photo_file_id' => 'Foto Profil',
    ];

    public function isComplete(): bool
    {
        return count($this->getMissingFields()) === 0;
    }

    public function getMissingFields(): array
    {
        $missing = [];
        foreach ($this->requiredFields as $field => $label) {
            if (!filled($this->{$field})) {
                $missing[$field] = $label;
            }
        }
        return $missing;
    }

    public function getCompletionPercentage(): int
    {
        $total = count($this->requiredFields);
        $filled = 0;
        foreach ($this->requiredFields as $field => $label) {
            if (filled($this->{$field})) {
                $filled++;
            }
        }
        return $total > 0 ? round(($filled / $total) * 100) : 0;
    }
}
