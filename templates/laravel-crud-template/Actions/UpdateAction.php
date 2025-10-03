<?php

namespace App\Http\Controllers\Admin\Actions\Entity;

use App\Http\Requests\Admin\Entity\UpdateRequest;
use App\Models\Entity;
use App\Models\TemporaryImage;
use App\Services\File\FileTransferService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

/**
 * エンティティ更新アクション
 *
 * 機能:
 * - バリデーション済みデータでのエンティティ更新
 * - 既存関連データの更新（カテゴリ同期）
 * - 既存画像の削除と新画像の登録
 * - リッチテキストエディタ内画像の処理
 * - トランザクション処理
 * - 権限チェック（作成者のみ編集可能）
 */
class UpdateAction
{
    public function __construct(
        private FileTransferService $fileTransferService
    ) {}

    /**
     * エンティティ更新処理を実行
     *
     * @param UpdateRequest $request バリデーション済みリクエスト
     * @param Entity $entity エンティティインスタンス
     * @return \Illuminate\Http\RedirectResponse
     */
    public function execute(UpdateRequest $request, Entity $entity)
    {
        try {
            $validated = $request->validated();
            $validated['id'] = $entity->id;

            // 権限チェック：現在のadminユーザーが作成したエンティティかをチェック
            if ($entity->create_admin_id !== Auth::user()->id) {
                abort(403, 'このエンティティを更新する権限がありません。');
            }

            Log::info('Entity update started', [
                'entity_id' => $entity->id,
                'admin_id' => Auth::user()->id
            ]);

            // リッチテキストエディタ内の画像を本登録し、パスを更新
            $validated['content_json'] = $this->processRichTextImages($validated['content_json']);

            // 更新者とタイムスタンプを設定
            $validated['update_admin_id'] = Auth::user()->id;
            $validated['ulid'] = Str::ulid(); // 新しいULIDを生成

            DB::transaction(function () use ($validated, $entity) {

                // エンティティ基本情報更新
                $entity->update($validated);

                // 関連データ（カテゴリ）の同期
                if (isset($validated['category_ids'])) {
                    $entity->categories()->sync($validated['category_ids']);
                }

                // 既存画像の削除
                $this->removeExistingImages($entity);

                // 新しい画像の処理
                $this->processThumbnailImage($entity, $validated);
                $this->processAdditionalImages($entity, $validated);

                Log::info('Entity updated successfully', [
                    'entity_id' => $entity->id,
                    'admin_id' => Auth::user()->id
                ]);
            });

            return redirect()
                ->route('admin.entity.index')
                ->with('success', 'エンティティ「' . $validated['name'] . '」の更新が完了しました');

        } catch (Throwable $e) {
            Log::error('Entity update failed', [
                'entity_id' => $id,
                'error' => $e->getMessage(),
                'admin_id' => Auth::user()->id
            ]);
            return redirect()
                ->back()
                ->with('error', 'エンティティの更新に失敗しました')
                ->withInput();
        }
    }

    /**
     * リッチテキストエディタ内の画像パスを処理
     *
     * @param string|null $contentJson
     * @return string|null
     */
    private function processRichTextImages(?string $contentJson): ?string
    {
        if (!$contentJson) return null;

        try {
            $content = json_decode($contentJson);

            foreach ($content as $section) {
                foreach ($section as $item) {
                    if (property_exists($item->insert ?? new \stdClass(), 'image')) {
                        $newPath = $this->fileTransferService->copyFileToDirectory(
                            $item->insert->image,
                            'rich-text'
                        );
                        if ($newPath) {
                            $item->insert->image = $newPath;
                        }
                    }
                }
            }

            return json_encode($content);
        } catch (\Exception $e) {
            Log::warning('Rich text image processing failed', [
                'error' => $e->getMessage()
            ]);
            return $contentJson;
        }
    }

    /**
     * 既存画像を削除
     *
     * @param Entity $entity
     * @return void
     */
    private function removeExistingImages(Entity $entity): void
    {
        $existingImages = $entity->images;

        foreach ($existingImages as $image) {
            if ($image->file_path) {
                $this->fileTransferService->deleteFile($image->file_path);
            }
        }

        // データベースから画像レコードを削除
        $entity->images()->delete();
    }

    /**
     * サムネイル画像を処理
     *
     * @param Entity $entity
     * @param array $validated
     * @return void
     */
    private function processThumbnailImage(Entity $entity, array $validated): void
    {
        if (!isset($validated['thumbnail']) || !$validated['thumbnail']) {
            return;
        }

        $thumbnailData = TemporaryImage::where('ulid', $validated['thumbnail'])
            ->first();

        if (!$thumbnailData) return;

        $thumbnailArray = $thumbnailData->toArray();
        $thumbnailArray['is_thumbnail'] = true;
        $thumbnailArray['file_path'] = $this->fileTransferService->copyFileToDirectory(
            $thumbnailArray['file_path'],
            'entity'
        );

        if ($thumbnailArray['file_path']) {
            $entity->images()->create($thumbnailArray);
        }
    }

    /**
     * 追加画像を処理
     *
     * @param Entity $entity
     * @param array $validated
     * @return void
     */
    private function processAdditionalImages(Entity $entity, array $validated): void
    {
        if (!isset($validated['additional_images']) || !$validated['additional_images']) {
            return;
        }

        $additionalImages = TemporaryImage::whereIn('ulid', $validated['additional_images'])
            ->get()
            ->toArray();

        foreach ($additionalImages as &$image) {
            $image['is_thumbnail'] = false;
            $image['file_path'] = $this->fileTransferService->copyFileToDirectory(
                $image['file_path'],
                'entity'
            );
        }

        // null file_pathを除外
        $validImages = array_filter($additionalImages, fn($img) => $img['file_path'] !== null);

        if (!empty($validImages)) {
            $entity->images()->createMany($validImages);
        }
    }
}