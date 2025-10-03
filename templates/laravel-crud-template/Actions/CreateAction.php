<?php

namespace App\Http\Controllers\Admin\Actions\Entity;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * エンティティ作成フォーム表示アクション
 *
 * 機能:
 * - 作成フォーム用のデータ準備
 * - 関連データ（カテゴリ）の取得
 * - デフォルト値の設定
 */
class CreateAction
{
    /**
     * 作成フォーム表示処理を実行
     *
     * @param Request $request
     * @return View
     */
    public function execute(Request $request): View
    {
        $adminUser = Auth::user();

        // フォームで使用する関連データを取得
        $categories = $adminUser->categories()->pluck('name', 'id');

        // ステータス選択肢
        $statusOptions = $this->getStatusOptions();

        // デフォルト値設定
        $defaults = $this->getDefaultValues();

        return view('admin.entity.create')
            ->with([
                'categories' => $categories,
                'statusOptions' => $statusOptions,
                'defaults' => $defaults,
            ]);
    }

    /**
     * ステータス選択肢を取得
     *
     * @return array
     */
    private function getStatusOptions(): array
    {
        return [
            0 => '非公開',
            1 => '公開',
        ];
    }

    /**
     * デフォルト値を取得
     *
     * @return array
     */
    private function getDefaultValues(): array
    {
        return [
            'is_public' => 0,
            'is_featured' => 0,
            'sort_order' => 0,
        ];
    }
}