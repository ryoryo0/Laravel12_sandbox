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
}
