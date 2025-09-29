<?php

namespace App\Http\Controllers\Admin\Actions\Entity;

use App\Http\Requests\Admin\Entity\StoreRequest;
use App\Models\Entity;
use App\Models\TemporaryImage;
use App\Services\File\FileTransferService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

/**
 * エンティティ作成アクション
 *
 * 機能:
 * - バリデーション済みデータでのエンティティ作成
 * - 関連データ（カテゴリ）の同期
 * - 画像ファイルの本登録（サムネイル・追加画像）
 * - リッチテキストエディタ内画像の処理
 * - トランザクション処理
 * - エラーハンドリングとログ記録
 */
class StoreAction
{
    public function __construct(
        private FileTransferService $fileTransferService
    ) {}

    /**
     * エンティティ作成処理を実行
     *
     * @param StoreRequest $request バリデーション済みリクエスト
     * @return \Illuminate\Http\RedirectResponse
     */
    public function execute(StoreRequest $request)
    {
        try {
            $validated = $request->validated();

            // リッチテキストエディタ内の画像を本登録し、パスを更新
            $validated['content_json'] = $this->processRichTextImages($validated['content_json']);

            // 作成者とULIDを設定
            $validated['create_admin_id'] = Auth::user()->id;
            $validated['ulid'] = Str::ulid();

            DB::transaction(function () use ($validated) {
                // エンティティ作成
                $entity = Entity::create($validated);

                // 関連データ（カテゴリ）の同期
                if (isset($validated['category_ids'])) {
                    $entity->categories()->sync($validated['category_ids']);
                }

                // サムネイル画像の処理
                $this->processThumbnailImage($entity, $validated);

                // 追加画像の処理
                $this->processAdditionalImages($entity, $validated);

                // 作成完了ログ
                Log::info('Entity created successfully', [
                    'entity_id' => $entity->id,
                    'admin_id' => Auth::user()->id
                ]);
            });

            return redirect()
                ->route('admin.entity.index')
                ->with('success', 'エンティティの登録が完了しました');

        } catch (Throwable $e) {
            Log::error('Entity creation failed', [
                'error' => $e->getMessage(),
                'admin_id' => Auth::user()->id
            ]);
            return redirect()
                ->back()
                ->with('error', 'エンティティの登録に失敗しました')
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