<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryFile extends Model
{
    protected $fillable = [
        'id',
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
