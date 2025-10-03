<?php

namespace App\Http\Controllers\Admin\Actions\Entity;

use App\Models\Entity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * エンティティ詳細表示アクション
 *
 * 機能:
 * - エンティティ詳細情報の取得と権限チェック
 * - 関連データ（カテゴリ、画像）の取得
 * - リッチテキストコンテンツの画像パス変換
 * - 表示用データの整形
 */
class ShowAction
{
    /**
     * 詳細表示処理を実行
     *
     * @param int $id エンティティID
     * @return View
     */
    public function execute(int $id): View
    {
        $adminUser = Auth::user();

        // エンティティ取得（権限チェック含む）
        $entity = Entity::with(['categories', 'images'])
            ->where('id', $id)
            ->where('create_admin_id', $adminUser->id)
            ->firstOrFail();

        // リッチテキストコンテンツ内の画像パスを表示用に変換
        if ($entity && $entity->content_json) {
            $entity->content_json = $this->convertContentImagePaths($entity->content_json);
        }

        // 表示用の追加データ準備
        $displayData = $this->prepareDisplayData($entity);

        return view('admin.entity.show', compact('entity'))
            ->with($displayData);
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
                'entity_id' => $id ?? null
            ]);
            return $contentJson;
        }
    }

    /**
     * 表示用データを準備
     *
     * @param Entity $entity
     * @return array
     */
    private function prepareDisplayData(Entity $entity): array
    {
        return [
            // ステータス表示用
            'statusLabel' => $entity->is_public ? '公開' : '非公開',
            'featuredLabel' => $entity->is_featured ? 'おすすめ' : '通常',

            // カテゴリ名をカンマ区切りで取得
            'categoryNames' => $entity->categories->pluck('name')->implode(', '),

            // 画像データの分類
            'thumbnailImage' => $entity->images->where('is_thumbnail', true)->first(),
            'additionalImages' => $entity->images->where('is_thumbnail', false),

            // 作成・更新情報
            'createdInfo' => [
                'date' => $entity->created_at->format('Y年m月d日 H:i'),
                'admin' => $entity->createAdmin->name ?? '不明',
            ],
            'updatedInfo' => [
                'date' => $entity->updated_at->format('Y年m月d日 H:i'),
                'admin' => $entity->updateAdmin->name ?? '不明',
            ],
        ];
    }
}