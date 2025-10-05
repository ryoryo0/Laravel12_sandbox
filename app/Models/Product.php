<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use App\Models\ProductImage;

class Product extends Model
{
    protected $fillable = [
        'id',
        'name',
        'description',
        'detail_json',
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
        return $this->belongsToMany(Category::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
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
                    'category_id'     => ['required','array', Rule::exists('categories', 'id')],
                    'create_admin_id' => ['required','integer', Rule::exists('admins', 'name')],
                    'code'            => ['required','string', 'max:255', 'unique:products.code'],
                    'ulid'            => ['required','string', 'max:255', 'unique:products.ulid'],
                    'is_public'       => ['required','boolean'],
                    'is_pick_up'      => ['required','boolean'],
                ];

        return $rules;
     }



    /**
     * 商品のサムネイル画像を1枚返す
     *
     * 中間テーブルの `is_thumbnail` フラグが true の画像を返します。
     * サムネイルが設定されていない場合は null を返します。
     *
     * @return \App\Models\Image|null
     */
    public function thumbnail(): ?ProductImage
    {
        return $this->images()
                    ->where('is_thumbnail', true)
                    ->first();
    }

    /**
     * サムネイル以外の画像一覧を返す
     *
     * 中間テーブルの `is_thumbnail` フラグが false の画像を返します。
     * 複数件存在する可能性があるため、コレクションで返ります。
     *
     * @return \Illuminate\Support\Collection<int, \App\Models\Image>
     */
    public function otherImages(): Collection
    {
        return $this->images()
                    ->where('is_thumbnail', false)
                    ->get();
    }
}
