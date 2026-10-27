<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermissionDefault extends Model
{
    protected $fillable = ['role', 'permission'];

    /** Permission names enabled by default for a role. */
    public static function forRole(string $role): array
    {
        return static::where('role', $role)->pluck('permission')->all();
    }
}
