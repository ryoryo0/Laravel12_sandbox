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
        return $this->belongsToMany(Product::class);
    }
 
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
