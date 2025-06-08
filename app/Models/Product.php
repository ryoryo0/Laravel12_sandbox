<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'create_admin_id',
        'ulid',
        'is_public',
        'is_pick_up',
    ];


     /**
     * relation
     */

     public function admin()
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
