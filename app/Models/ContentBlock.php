<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Editable public site content (Terms, Privacy, footer blurb). The Super Admin
 * edits the body; the landing/legal pages render it.
 */
class ContentBlock extends Model
{
    use HasUuids;

    protected $fillable = ['key', 'title', 'body', 'updated_by'];
}
