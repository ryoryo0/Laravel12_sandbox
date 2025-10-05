<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventImage extends Model
{
    protected $fillable = [
        'event_id',
        'original_filename',
        'stored_filename',
        'ulid',
        'file_path',
        'file_size',
        'file_extension',
        'mime_type',
    ];

    /**
     * ---------------------------------------------------------------------------------------------------------------------------------------------
     * Relation
     * ---------------------------------------------------------------------------------------------------------------------------------------------
     */

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
