<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $incrementing = false;

    protected $primaryKey = 'key';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['key', 'value', 'type', 'label', 'group'];

    /** Cast the stored string value to its declared type. */
    public function typedValue(): mixed
    {
        return match ($this->type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $this->value,
            default => $this->value,
        };
    }

    /** Normalize an incoming value to a string for storage. */
    public static function normalize(string $type, mixed $value): ?string
    {
        return match ($type) {
            'boolean' => $value ? '1' : '0',
            'integer' => (string) (int) $value,
            default => $value === null ? null : (string) $value,
        };
    }
}
