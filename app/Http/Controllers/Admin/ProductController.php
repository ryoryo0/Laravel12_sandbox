<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Actions\Product\CreateAction;
use App\Http\Controllers\Admin\Actions\Product\DestroyAction;
use App\Http\Controllers\Admin\Actions\Product\EditAction;
use App\Http\Controllers\Admin\Actions\Product\ImageAction;
use App\Http\Controllers\Admin\Actions\Product\IndexAction;
use App\Http\Controllers\Admin\Actions\Product\ShowAction;
use App\Http\Controllers\Admin\Actions\Product\StoreAction;
use App\Http\Controllers\Admin\Actions\Product\UpdateAction;
use App\Http\Requests\Admin\Product\IndexRequest;
use App\Http\Requests\Admin\Product\StoreRequest;
use App\Http\Requests\Admin\Product\UpdateRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
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
     * 商品一覧表示
     */
    public function index(IndexRequest $request): View
    {
        return $this->indexAction->execute($request);
    }

    /**
     * 商品作成フォーム表示
     */
    public function create(Request $request): View
    {
        return $this->createAction->execute($request);
    }

    /**
     * 商品作成処理
     */
    public function store(StoreRequest $request)
    {
        return $this->storeAction->execute($request);
    }

    /**
     * 商品詳細表示
     */
    public function show(Product $product): View
    {
        return $this->showAction->execute($product);
    }

    /**
     * 商品編集フォーム表示
     */
    public function edit(Product $product): View
    {
        return $this->editAction->execute($product);
    }

    /**
     * 商品更新処理
     */
    public function update(UpdateRequest $request, Product $product)
    {
        return $this->updateAction->execute($request, $product);
    }

    /**
     * 商品削除処理
     */
    public function destroy(Product $product)
    {
        return $this->destroyAction->execute($product);
    }

    /**
     * 商品画像取得API
     */
    public function image(string $ulid): JsonResponse
    {
        return $this->imageAction->execute($ulid);
    }
}