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
        'price',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_pick_up' => 'boolean',
    ];


     /**
     * --------------------------------------------------------------------------------------------------------------------------------------------- 
     * Relation
     * --------------------------------------------------------------------------------------------------------------------------------------------- 
     */

     
    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
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

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_product')->withTimestamps();
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
    public function getThumbnail(): ?ProductImage
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
    public function getOtherImages(): Collection
    {
        return $this->images()
                    ->where('is_thumbnail', false)
                    ->get();
    }


    /**
     * 新商品かどうかを判定
     *
     * 作成日から30日以内の商品を新商品とする
     *
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->created_at->diffInDays(now()) <= 30;
    }


    /**
     * イベント適用商品か判定
     *
     * 作成日から30日以内の商品を新商品とする
     *
     * @return bool
     */
    public function hasEvent(): bool
    {
        $count =  $this->events->filter(function ($event) {
            return $event->isActive();
        })->count();

        $result = $count > 0;

        return $result;
    }

  
    /**
     * 端数を切り捨てた価格を返す
     *
     * @return int
     */
    public function getPriceFloor(): int
    {
        $result = floor($this->price);
        return $result;
    }

    
    /**
     * 表示用の文字列の価格を返す
     *
     * @return string
     */
    public function displayPrice(): string
    {
        $result = number_format($this->getPriceFloor());
        return $result;
    }


    /**
     * 割引適用後の価格を取得（最安値）
     *
     * @return string|null
     */
    public function getDiscountedPrice(): string|null
    {
        if (!$this->price) {
            return null;
        }

        $originalPrice = $this->price;

        // 商品（Product）経由でイベントを取得
        $activeEvents = $this->events->filter(function ($event) {
            return $event->isActive();
        });

        // アクティブなイベントがない場合は元の価格を返す
        if ($activeEvents->isEmpty()) {
            return number_format($originalPrice);
        }

        // 各イベントの割引適用後の価格を計算し、最安値を取得
        $minPrice = $activeEvents->map(function ($event) use ($originalPrice) {
            return $event->calcDiscountedPrice($originalPrice);
        })->min();

        return number_format($minPrice);
    }
}
