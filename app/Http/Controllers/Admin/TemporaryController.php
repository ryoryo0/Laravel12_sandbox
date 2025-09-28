<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Actions\Temporary\ShowAction;
use App\Http\Controllers\Admin\Actions\Temporary\UploadAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TemporaryController extends Controller
{
    public function __construct(
        private UploadAction $uploadAction,
        private ShowAction $showAction
    ) {}

    /**
     * 一時画像アップロード処理
     */
    public function __invoke(Request $request): JsonResponse
    {
        return $this->uploadAction->execute($request);
    }

    /**
     * ULIDから画像情報を取得する
     */
    public function show(string $ulid): JsonResponse
    {
        return $this->showAction->execute($ulid);
    }
}