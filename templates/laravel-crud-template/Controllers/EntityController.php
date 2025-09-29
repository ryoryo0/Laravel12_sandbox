<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Actions\Entity\CreateAction;
use App\Http\Controllers\Admin\Actions\Entity\DestroyAction;
use App\Http\Controllers\Admin\Actions\Entity\EditAction;
use App\Http\Controllers\Admin\Actions\Entity\ImageAction;
use App\Http\Controllers\Admin\Actions\Entity\IndexAction;
use App\Http\Controllers\Admin\Actions\Entity\ShowAction;
use App\Http\Controllers\Admin\Actions\Entity\StoreAction;
use App\Http\Controllers\Admin\Actions\Entity\UpdateAction;
use App\Http\Requests\Admin\Entity\IndexRequest;
use App\Http\Requests\Admin\Entity\StoreRequest;
use App\Http\Requests\Admin\Entity\UpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * エンティティ管理コントローラー
 *
 * 機能:
 * - 一覧表示・検索・フィルタリング
 * - 作成・編集・削除
 * - 詳細表示
 * - 画像管理（アップロード・表示）
 * - カテゴリ関連付け
 * - バルクアクション対応
 */
class EntityController extends Controller
{
    public function __construct(
        private IndexAction $indexAction,
        private CreateAction $createAction,
        private StoreAction $storeAction,
        private ShowAction $showAction,
        private EditAction $editAction,
        private UpdateAction $updateAction,
        private DestroyAction $destroyAction,
        private ImageAction $imageAction
    ) {}

    /**
     * エンティティ一覧表示
     *
     * @param IndexRequest $request 検索・フィルタパラメータ
     * @return View
     */
    public function index(IndexRequest $request): View
    {
        return $this->indexAction->execute($request);
    }

    /**
     * エンティティ作成フォーム表示
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        return $this->createAction->execute($request);
    }

    /**
     * エンティティ作成処理
     *
     * @param StoreRequest $request バリデーション済みリクエスト
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        return $this->storeAction->execute($request);
    }

    /**
     * エンティティ詳細表示
     *
     * @param int $id エンティティID
     * @return View
     */
    public function show(int $id): View
    {
        return $this->showAction->execute($id);
    }

    /**
     * エンティティ編集フォーム表示
     *
     * @param Request $request
     * @return View
     */
    public function edit(Request $request): View
    {
        return $this->editAction->execute($request);
    }

    /**
     * エンティティ更新処理
     *
     * @param UpdateRequest $request バリデーション済みリクエスト
     * @param int $id エンティティID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, Entity $entity)
    {
        return $this->updateAction->execute($request, $id);
    }

    /**
     * エンティティ削除処理
     *
     * @param int $id エンティティID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        return $this->destroyAction->execute($id);
    }

    /**
     * エンティティ画像取得API
     *
     * @param string $ulid 画像ULID
     * @return JsonResponse
     */
    public function image(string $ulid): JsonResponse
    {
        return $this->imageAction->execute($ulid);
    }
}