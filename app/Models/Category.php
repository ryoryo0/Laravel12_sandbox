<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
    ];


     /**
     * relation
     */

    public function products()
    {
        return $this->belongsTo(Product::class, 'categories_products', 'category_id', 'product_id');
    }
 
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
