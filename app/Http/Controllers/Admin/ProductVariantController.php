<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Actions\ProductVariant\CreateAction;
use App\Http\Controllers\Admin\Actions\ProductVariant\DestroyAction;
use App\Http\Controllers\Admin\Actions\ProductVariant\EditAction;
use App\Http\Controllers\Admin\Actions\ProductVariant\IndexAction;
use App\Http\Controllers\Admin\Actions\ProductVariant\StoreAction;
use App\Http\Controllers\Admin\Actions\ProductVariant\UpdateAction;
use App\Http\Requests\Admin\ProductVariant\IndexRequest;
use App\Http\Requests\Admin\ProductVariant\StoreRequest;
use App\Http\Requests\Admin\ProductVariant\UpdateRequest;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductVariantController extends Controller
{
    public function __construct(
        private IndexAction $indexAction,
        private CreateAction $createAction,
        private StoreAction $storeAction,
        private EditAction $editAction,
        private UpdateAction $updateAction,
        private DestroyAction $destroyAction
    ) {}

    /**
     * 在庫一覧表示
     */
    public function index(IndexRequest $request): View
    {
        return $this->indexAction->execute($request);
    }

    /**
     * 在庫作成フォーム表示
     */
    public function create(Request $request): View
    {
        return $this->createAction->execute($request);
    }

    /**
     * 在庫作成処理
     */
    public function store(StoreRequest $request)
    {
        return $this->storeAction->execute($request);
    }

    /**
     * 在庫編集フォーム表示
     */
    public function edit(ProductVariant $productVariant): View
    {
        return $this->editAction->execute($productVariant);
    }

    /**
     * 在庫更新処理
     */
    public function update(UpdateRequest $request, ProductVariant $productVariant)
    {
        return $this->updateAction->execute($request, $productVariant);
    }

    /**
     * 在庫削除処理
     */
    public function destroy(ProductVariant $productVariant)
    {
        return $this->destroyAction->execute($productVariant);
    }
}
