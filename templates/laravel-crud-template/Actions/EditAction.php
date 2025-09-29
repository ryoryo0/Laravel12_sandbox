<?php

namespace App\Http\Controllers\Admin\Actions\Entity;

use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * エンティティ編集フォーム表示アクション
 *
 * 機能:
 * - 編集対象エンティティの取得と権限チェック
 * - 関連データ（カテゴリ、画像）の取得
 * - リッチテキストコンテンツの画像パス変換
 * - フォーム表示用データの整形
 */
class EditAction
{
    /**
     * 編集フォーム表示処理を実行
     *
     * @param Request $request
     * @return View
     */
    public function execute(Request $request): View
    {
        $adminUser = Auth::user();

        // エンティティ取得（権限チェック含む）
        $entity = Entity::where('id', $request->id)
            ->where('create_admin_id', $adminUser->id)
            ->with('categories', 'images')
            ->firstOrFail();

        // リッチテキストコンテンツ内の画像パスを表示用に変換
        if ($entity && $entity->content_json) {
            $entity->content_json = $this->convertContentImagePaths($entity->content_json);
        }

        // フォームで使用する関連データを取得
        $categories = $adminUser->categories()->pluck('name', 'id');

        // ステータス選択肢
        $statusOptions = $this->getStatusOptions();

        // 既存の選択済みカテゴリIDを取得
        $selectedCategoryIds = $entity->categories->pluck('id')->toArray();

        return view('admin.entity.edit')
            ->with([
                'entity' => $entity,
                'categories' => $categories,
                'statusOptions' => $statusOptions,
                'selectedCategoryIds' => $selectedCategoryIds,
            ]);
    }

    /**
     * リッチテキストコンテンツ内の画像パスを表示用に変換
     *
     * @param string $contentJson
     * @return string
     */
    private function convertContentImagePaths(string $contentJson): string
    {
        try {
            $data = json_decode($contentJson, true);

            if (!$data || !isset($data['ops'])) {
                return $contentJson;
            }

            foreach ($data['ops'] as &$op) {
                if (isset($op['insert']['image'])) {
                    $imagePath = $op['insert']['image'];

                    // 相対パスの場合はassetパスに変換
                    if (strpos($imagePath, 'images/') === 0) {
                        $op['insert']['image'] = asset('storage/' . $imagePath);
                    }
                }
            }

            return json_encode($data);

        } catch (\Exception $e) {
            Log::error('Content image path conversion failed', [
                'error' => $e->getMessage(),
                'entity_id' => $this->entity->id ?? null
            ]);
            return $contentJson;
        }
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
}