<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = [
        'name',
    ];


     /**
     * relation
     */

     public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
