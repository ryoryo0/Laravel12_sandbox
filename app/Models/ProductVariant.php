<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class ProductVariant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'color',
        'size',
        'stock',
    ];

    /**
     * ---------------------------------------------------------------------------------------------------------------------------------------------
     * Relation
     * ---------------------------------------------------------------------------------------------------------------------------------------------
     */

    public function product()
    {
        return $this->belongsTo(Product::class);
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
    public static function getBaseRules(): array
    {
        $rules = [
            'product_id' => ['required', 'integer', Rule::exists('products', 'id')],
            'color'      => ['required', 'string', 'max:255'],
            'size'       => ['required', 'string', 'max:255'],
            'stock'      => ['required', 'integer', 'min:0'],
        ];

        return $rules;
    }


    /**
     * 割引適用後の価格を取得（最安値）
     *
     * @return string
     */
    public function getDiscountedPrice(): string
    {
        if (!$this->price) {
            return '0';
        }

        $originalPrice = $this->price;

        // 商品（Product）経由でイベントを取得
        $activeEvents = $this->product->events->filter(function ($event) {
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
