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

     public function productCategory()
    {
        return $this->hasOne(ProductCategory::class);
    }
}
