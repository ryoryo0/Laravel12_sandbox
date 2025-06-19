<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Product extends Model
{
    protected $fillable = [
        'id',
        'name',
        'description',
        'category_id',
        'create_admin_id',
        'ulid',
        'is_public',
        'is_pick_up',
    ];


     /**
     * --------------------------------------------------------------------------------------------------------------------------------------------- 
     * Relation
     * --------------------------------------------------------------------------------------------------------------------------------------------- 
     */

     
     public function admin()
    {
        return $this->belongsTo(ProductCategory::class);
    }


     /**
     * --------------------------------------------------------------------------------------------------------------------------------------------- 
     * Method
     * --------------------------------------------------------------------------------------------------------------------------------------------- 
     */

     
     /**
      * 基本のバリデーションルールを取得
      *
      * @return array
      */
     public static function getBaseRule (): array
     {
        $rule = [
                    'id' => ['nullable','integer'],
                    'name' => ['nullable','string', 'max:255'],
                    'description' => ['nullable','string', 'max:255'],
                    'category_id' => ['nullable','array', Rule::exists('product_categories', 'id')],
                    'create_admin_id' =>[ 'nullable','integer', Rule::exists('admins', 'name')],
                    'ulid' => ['nullable','string', 'max:255', Rule::exists('products', 'ulid')],
                    'is_public' =>[ 'nullable','boolean'],
                    'is_pick_up' => ['nullable','boolean'],
                ];

        return $rule;
     }
}
