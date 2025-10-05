<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Category extends Model
{
    protected $fillable = [
        'name',
        'create_admin_id',
    ];

    /**
     * ---------------------------------------------------------------------------------------------------------------------------------------------
     * Relation
     * ---------------------------------------------------------------------------------------------------------------------------------------------
     */

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'create_admin_id');
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
        ];
    }

    /**
     * 更新用のバリデーションルールを取得
     *
     * @param int $categoryId
     * @return array
     */
    public static function getUpdateRules(int $categoryId): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
