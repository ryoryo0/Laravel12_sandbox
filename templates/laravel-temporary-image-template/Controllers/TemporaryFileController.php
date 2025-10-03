<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Actions\TemporaryFile\ShowAction;
use App\Http\Controllers\Admin\Actions\TemporaryFile\UploadAction;
use App\Http\Requests\Admin\TemporaryFile\UploadRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 一時ファイル管理コントローラー
 *
 * 機能:
 * - ファイルの一時アップロード処理
 * - アップロード済みファイルの情報取得
 * - 画像リサイズ・圧縮機能
 * - セキュアなファイル管理
 * - API形式でのレスポンス
 *
 * 用途:
 * - フォーム送信前の画像プレビュー
 * - リッチテキストエディタでの画像挿入
 * - 段階的なファイルアップロード
 * - ファイル検証とメタデータ取得
 */
class TemporaryFileController extends Controller
{
    public function __construct(
        private UploadAction $uploadAction,
        private ShowAction $showAction
    ) {}

    /**
     * ファイル一時アップロード処理
     *
     * @param UploadRequest $request バリデーション済みアップロードリクエスト
     * @return JsonResponse アップロード結果（URL、ファイル名、ULID等）
     */
    public function __invoke(UploadRequest $request): JsonResponse
    {
        return $this->uploadAction->execute($request);
    }

    /**
     * アップロード済みファイル情報取得
     *
     * @param string $ulid ファイルの一意識別子
     * @return JsonResponse ファイル情報（URL、メタデータ等）
     */
    public function show(string $ulid): JsonResponse
    {
        return $this->showAction->execute($ulid);
    }

    /**
     * 複数ファイル一括アップロード（オプション）
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadMultiple(Request $request): JsonResponse
    {
        // 複数ファイルアップロードが必要な場合は実装
        // return $this->multipleUploadAction->execute($request);
        return response()->json(['message' => 'Multiple upload not implemented'], 501);
    }

    /**
     * 一時ファイル削除（オプション）
     *
     * @param string $ulid
     * @return JsonResponse
     */
    public function destroy(string $ulid): JsonResponse
    {
        // 一時ファイル削除が必要な場合は実装
        // return $this->deleteAction->execute($ulid);
        return response()->json(['message' => 'Delete not implemented'], 501);
    }
}