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
        'detail',
        'create_admin_id',
        'code',
        'ulid',
        'is_public',
        'is_pick_up',
    ];


     /**
     * --------------------------------------------------------------------------------------------------------------------------------------------- 
     * Relation
     * --------------------------------------------------------------------------------------------------------------------------------------------- 
     */

     
    public function categories()
    {
        return $this->belongsTo(Category::class, 'categories_products', 'product_id', 'category_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
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
     public static function getBaseRules (): array
     {
        $rules = [
                    'id'              => ['required','integer'],
                    'name'            => ['required','string', 'max:255'],
                    'description'     => ['required','string', 'max:255'],
                    'category_id'     => ['required','array', Rule::exists('product_categories', 'id')],
                    'create_admin_id' => ['required','integer', Rule::exists('admins', 'name')],
                    'code'            => ['required','string', 'max:255', 'unique:products.code'],
                    'ulid'            => ['required','string', 'max:255', 'unique:products.ulid'],
                    'is_public'       => ['required','boolean'],
                    'is_pick_up'      => ['required','boolean'],
                ];

        return $rules;
     }
}
