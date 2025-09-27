<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryImage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'original_filename',
        'stored_filename',
        'ulid',
        'file_path',
        'file_size',
        'ulid',
        'file_extension',
        'mime_type',
    ];
}
