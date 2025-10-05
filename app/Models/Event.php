<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'discount_type',
        'discount_rate',
        'discount_amount',
        'start_date',
        'end_date',
        'is_active',
        'create_admin_id',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'discount_rate' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    /**
     * ---------------------------------------------------------------------------------------------------------------------------------------------
     * Relation
     * ---------------------------------------------------------------------------------------------------------------------------------------------
     */

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'create_admin_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'event_product')->withTimestamps();
    }

    public function image()
    {
        return $this->hasOne(EventImage::class);
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
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'discount_type' => ['required', Rule::in(['rate', 'amount'])],
            'discount_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'is_active' => ['required', 'boolean'],
            'product_ids' => ['nullable', 'array'],
            'product_ids.*' => ['integer', Rule::exists('products', 'id')],
        ];
    }

    /**
     * イベントのサムネイル画像を1枚返す
     *
     * @return \App\Models\EventImage|null
     */
    public function thumbnail(): ?EventImage
    {
        return $this->image;
    }

    /**
     * イベントが有効期間内かどうか
     *
     * @return bool
     */
    public function isActive(): bool
    {
        $now = now();
        return $this->is_active
            && $this->start_date <= $now
            && $this->end_date >= $now;
    }

    /**
     * 割引額を計算
     *
     * @param float $price
     * @return float
     */
    public function calculateDiscountedPrice(float $price): float
    {
        if ($this->discount_type === 'rate') {
            return $price * (100 - $this->discount_rate) / 100;
        } else {
            return max(0, $price - $this->discount_amount);
        }
    }
}
