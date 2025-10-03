<?php

namespace App\Http\Controllers\Admin\Actions\Entity;

use App\Models\Entity;
use App\Services\File\FileTransferService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * エンティティ削除アクション
 *
 * 機能:
 * - エンティティ削除と権限チェック
 * - 関連データ（カテゴリ、画像）の削除
 * - ファイルシステムから画像ファイルの削除
 * - トランザクション処理
 * - ソフトデリート対応
 */
class DestroyAction
{
    public function __construct(
        private FileTransferService $fileTransferService
    ) {}

    /**
     * エンティティ削除処理を実行
     *
     * @param int $id エンティティID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function execute(int $id)
    {
        try {
            $adminUser = Auth::user();

            // エンティティ取得と権限チェック
            $entity = Entity::where('id', $id)
                ->where('create_admin_id', $adminUser->id)
                ->first();

            if (!$entity) {
                return redirect()
                    ->route('admin.entity.index')
                    ->with('error', '削除権限がないか、エンティティが存在しません。');
            }

            $entityName = $entity->name;

            DB::transaction(function () use ($entity) {
                // 関連画像ファイルをファイルシステムから削除
                $this->removeAssociatedFiles($entity);

                // データベースから画像レコードを削除
                $entity->images()->delete();

                // 関連カテゴリーの関連付けを削除
                $entity->categories()->detach();

                // エンティティ削除（ソフトデリート）
                $entity->delete();

                Log::info('Entity deleted successfully', [
                    'entity_id' => $entity->id,
                    'entity_name' => $entity->name,
                    'admin_id' => Auth::user()->id
                ]);
            });

            return redirect()
                ->route('admin.entity.index')
                ->with('success', 'エンティティ「' . $entityName . '」を削除しました。');

        } catch (Throwable $e) {
            Log::error('Entity deletion failed', [
                'entity_id' => $id,
                'error' => $e->getMessage(),
                'admin_id' => Auth::user()->id
            ]);
            return redirect()
                ->route('admin.entity.index')
                ->with('error', 'エンティティの削除に失敗しました。');
        }
    }

    /**
     * 関連ファイルを削除
     *
     * @param Entity $entity
     * @return void
     */
    private function removeAssociatedFiles(Entity $entity): void
    {
        // 画像ファイルを削除
        $images = $entity->images;
        foreach ($images as $image) {
            if ($image->file_path) {
                $this->fileTransferService->deleteFile($image->file_path);
            }
        }

        // リッチテキストコンテンツ内の画像も削除
        if ($entity->content_json) {
            $this->removeContentImages($entity->content_json);
        }
    }

    /**
     * リッチテキストコンテンツ内の画像を削除
     *
     * @param string $contentJson
     * @return void
     */
    private function removeContentImages(string $contentJson): void
    {
        try {
            $data = json_decode($contentJson, true);

            if (!$data || !isset($data['ops'])) {
                return;
            }

            foreach ($data['ops'] as $op) {
                if (isset($op['insert']['image'])) {
                    $imagePath = $op['insert']['image'];

                    // 相対パスの場合は削除
                    if (strpos($imagePath, 'images/') === 0) {
                        $this->fileTransferService->deleteFile($imagePath);
                    }
                }
            }

        } catch (\Exception $e) {
            Log::warning('Content image cleanup failed', [
                'error' => $e->getMessage()
            ]);
        }
    }
}