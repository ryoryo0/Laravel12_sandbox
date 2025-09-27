<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'product_id',
        'original_filename',
        'stored_filename',
        'ulid',
        'file_path',
        'file_size',
        'ulid',
        'file_extension',
        'mime_type',
        'is_thumbnail',
    ];

    /**
     * --------------------------------------------------------------------------------------------------------------------------------------------- 
     * Relation
     * --------------------------------------------------------------------------------------------------------------------------------------------- 
     */

     public function products()
     {
         return $this->belongsTo(Product::class);
     }


    /**
     * --------------------------------------------------------------------------------------------------------------------------------------------- 
     * Method
     * --------------------------------------------------------------------------------------------------------------------------------------------- 
     */
}
