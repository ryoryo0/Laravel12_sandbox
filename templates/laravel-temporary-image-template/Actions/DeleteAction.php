<?php

namespace App\Http\Controllers\Admin\Actions\TemporaryFile;

use App\Models\TemporaryFile;
use App\Services\File\FileTransferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * 一時ファイル削除アクション
 *
 * 機能:
 * - 一時ファイルの物理削除
 * - データベースレコードの削除
 * - 権限チェック
 * - クリーンアップ処理
 *
 * 用途:
 * - ユーザーによる明示的な削除
 * - アップロード取り消し
 * - 古いファイルの自動削除
 * - エラー時のクリーンアップ
 */
class DeleteAction
{
    public function __construct(
        private FileTransferService $fileTransferService
    ) {}

    /**
     * ファイル削除処理を実行
     *
     * @param string $ulid ファイルの一意識別子
     * @return JsonResponse
     */
    public function execute(string $ulid): JsonResponse
    {
        try {
            // ファイル情報を取得
            $temporaryFile = TemporaryFile::where('ulid', $ulid)->first();

            if (!$temporaryFile) {
                return $this->createNotFoundResponse($ulid);
            }

            // 権限チェック（必要に応じて実装）
            if (!$this->canDeleteFile($temporaryFile)) {
                return $this->createUnauthorizedResponse($ulid);
            }

            // ファイル削除処理
            $deletionResult = $this->deleteFileAndRecord($temporaryFile);

            if ($deletionResult['success']) {
                Log::info('Temporary file deleted successfully', [
                    'ulid' => $ulid,
                    'file_path' => $temporaryFile->file_path,
                    'user_id' => auth()->id()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'ファイルが正常に削除されました。',
                    'ulid' => $ulid
                ]);
            } else {
                return response()->json([
                    'error' => 'Partial deletion',
                    'message' => 'ファイルの削除が部分的に失敗しました。',
                    'details' => $deletionResult
                ], 500);
            }

        } catch (Throwable $e) {
            Log::error('Temporary file deletion failed', [
                'ulid' => $ulid,
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'error' => 'Internal server error',
                'message' => 'ファイルの削除に失敗しました。'
            ], 500);
        }
    }

    /**
     * 複数ファイルを一括削除
     *
     * @param array $ulids ULID配列
     * @return JsonResponse
     */
    public function deleteMultiple(array $ulids): JsonResponse
    {
        $results = [];
        $successCount = 0;
        $failureCount = 0;

        foreach ($ulids as $ulid) {
            try {
                $temporaryFile = TemporaryFile::where('ulid', $ulid)->first();

                if (!$temporaryFile) {
                    $results[$ulid] = ['status' => 'not_found', 'message' => 'ファイルが見つかりません'];
                    $failureCount++;
                    continue;
                }

                if (!$this->canDeleteFile($temporaryFile)) {
                    $results[$ulid] = ['status' => 'unauthorized', 'message' => '削除権限がありません'];
                    $failureCount++;
                    continue;
                }

                $deletionResult = $this->deleteFileAndRecord($temporaryFile);

                if ($deletionResult['success']) {
                    $results[$ulid] = ['status' => 'success', 'message' => '削除完了'];
                    $successCount++;
                } else {
                    $results[$ulid] = ['status' => 'error', 'message' => '削除失敗', 'details' => $deletionResult];
                    $failureCount++;
                }

            } catch (Throwable $e) {
                $results[$ulid] = ['status' => 'error', 'message' => $e->getMessage()];
                $failureCount++;
            }
        }

        Log::info('Multiple temporary files deletion completed', [
            'total' => count($ulids),
            'success' => $successCount,
            'failure' => $failureCount,
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'summary' => [
                'total' => count($ulids),
                'success' => $successCount,
                'failure' => $failureCount
            ],
            'results' => $results
        ]);
    }

    /**
     * ファイルとレコードを削除
     *
     * @param TemporaryFile $temporaryFile
     * @return array
     */
    private function deleteFileAndRecord(TemporaryFile $temporaryFile): array
    {
        $result = [
            'success' => false,
            'physical_file_deleted' => false,
            'database_record_deleted' => false,
            'errors' => []
        ];

        // 物理ファイルの削除
        try {
            if ($this->fileTransferService->fileExists($temporaryFile->file_path)) {
                $result['physical_file_deleted'] = $this->fileTransferService->deleteFile($temporaryFile->file_path);
            } else {
                $result['physical_file_deleted'] = true; // ファイルが存在しない場合は成功とみなす
            }
        } catch (Throwable $e) {
            $result['errors'][] = 'Physical file deletion failed: ' . $e->getMessage();
        }

        // データベースレコードの削除
        try {
            $result['database_record_deleted'] = $temporaryFile->delete();
        } catch (Throwable $e) {
            $result['errors'][] = 'Database record deletion failed: ' . $e->getMessage();
        }

        $result['success'] = $result['physical_file_deleted'] && $result['database_record_deleted'];

        return $result;
    }

    /**
     * ファイル削除権限をチェック
     *
     * @param TemporaryFile $temporaryFile
     * @return bool
     */
    private function canDeleteFile(TemporaryFile $temporaryFile): bool
    {
        // 基本的な権限チェック
        if (!auth()->check()) {
            return false;
        }

        // 管理者は全てのファイルを削除可能
        if (auth()->user()->hasRole('admin')) {
            return true;
        }

        // ファイルが作成されてから一定時間経過後は削除不可（オプション）
        $maxAge = config('temporary_files.max_delete_age_hours', 24);
        if ($temporaryFile->created_at->diffInHours(now()) > $maxAge) {
            return false;
        }

        // その他のビジネスロジックに応じた権限チェック
        // 例：ファイルの作成者のみ削除可能、特定の状態のファイルは削除不可など

        return true;
    }

    /**
     * 古いファイルを自動削除（バッチ処理用）
     *
     * @param int $olderThanHours 指定時間より古いファイルを削除
     * @return array 削除結果
     */
    public function cleanupOldFiles(int $olderThanHours = 48): array
    {
        $cutoffTime = now()->subHours($olderThanHours);

        $oldFiles = TemporaryFile::where('created_at', '<', $cutoffTime)->get();

        $results = [
            'total_found' => $oldFiles->count(),
            'deleted' => 0,
            'errors' => 0,
            'details' => []
        ];

        foreach ($oldFiles as $file) {
            try {
                $deletionResult = $this->deleteFileAndRecord($file);

                if ($deletionResult['success']) {
                    $results['deleted']++;
                } else {
                    $results['errors']++;
                    $results['details'][] = [
                        'ulid' => $file->ulid,
                        'error' => $deletionResult['errors']
                    ];
                }

            } catch (Throwable $e) {
                $results['errors']++;
                $results['details'][] = [
                    'ulid' => $file->ulid,
                    'error' => $e->getMessage()
                ];
            }
        }

        Log::info('Temporary files cleanup completed', $results);

        return $results;
    }

    /**
     * ファイルが見つからない場合のレスポンス
     *
     * @param string $ulid
     * @return JsonResponse
     */
    private function createNotFoundResponse(string $ulid): JsonResponse
    {
        return response()->json([
            'error' => 'File not found',
            'message' => '指定されたファイルが見つかりません。',
            'ulid' => $ulid
        ], 404);
    }

    /**
     * 権限がない場合のレスポンス
     *
     * @param string $ulid
     * @return JsonResponse
     */
    private function createUnauthorizedResponse(string $ulid): JsonResponse
    {
        return response()->json([
            'error' => 'Unauthorized',
            'message' => 'このファイルを削除する権限がありません。',
            'ulid' => $ulid
        ], 403);
    }
}