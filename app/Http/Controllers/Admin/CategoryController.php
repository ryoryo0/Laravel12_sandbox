<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Actions\Category\DestroyAction;
use App\Http\Controllers\Admin\Actions\Category\IndexAction;
use App\Http\Controllers\Admin\Actions\Category\ShowAction;
use App\Http\Controllers\Admin\Actions\Category\StoreAction;
use App\Http\Controllers\Admin\Actions\Category\UpdateAction;
use App\Http\Requests\Admin\Category\IndexRequest;
use App\Http\Requests\Admin\Category\StoreRequest;
use App\Http\Requests\Admin\Category\UpdateRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(
        private IndexAction $indexAction,
        private StoreAction $storeAction,
        private ShowAction $showAction,
        private UpdateAction $updateAction,
        private DestroyAction $destroyAction
    ) {}

    /**
     * カテゴリー一覧表示
     */
    public function index(IndexRequest $request): View
    {
        return $this->indexAction->execute($request);
    }

    /**
     * カテゴリー作成処理
     */
    public function store(StoreRequest $request): JsonResponse
    {
        return $this->storeAction->execute($request);
    }

    /**
     * カテゴリー詳細取得
     */
    public function show(Category $category): JsonResponse
    {
        return $this->showAction->execute($category);
    }

    /**
     * カテゴリー更新処理
     */
    public function update(UpdateRequest $request, Category $category): JsonResponse
    {
        return $this->updateAction->execute($request, $category);
    }

    /**
     * カテゴリー削除処理
     */
    public function destroy(Category $category): JsonResponse
    {
        return $this->destroyAction->execute($category);
    }
}
