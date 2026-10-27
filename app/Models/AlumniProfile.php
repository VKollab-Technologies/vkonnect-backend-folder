<?php

namespace App\Models;

use App\Support\PanHash;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlumniProfile extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'vkollab_id',
        'pan',
        'designation',
        'department',
        'current_organization',
        'phone',
        'location',
        'joined_on',
        'exited_on',
        'last_working_location',
        'band',
        'supervisor_name',
        'supervisor_email',
        'uan_number',
        'pf_number',
        'bio',
        'profile_completion',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $model) {
            if (! $model->isDirty('pan')) {
                return;
            }

            $normalised = AlumniMaster::normalizePan($model->pan);
            $model->pan = $normalised;
            $model->pan_hash = PanHash::make($normalised);
        });
    }

    protected function casts(): array
    {
        return [
            // Mirrors AlumniMaster. No unique index here -- the roster owns
            // PAN uniqueness -- but the hash is still kept so the two tables
            // can be matched without decrypting either.
            'pan' => 'encrypted',
            'joined_on' => 'date',
            'exited_on' => 'date',
            'profile_completion' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
