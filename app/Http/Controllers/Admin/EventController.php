<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Actions\Event\CreateAction;
use App\Http\Controllers\Admin\Actions\Event\DestroyAction;
use App\Http\Controllers\Admin\Actions\Event\EditAction;
use App\Http\Controllers\Admin\Actions\Event\ImageAction;
use App\Http\Controllers\Admin\Actions\Event\IndexAction;
use App\Http\Controllers\Admin\Actions\Event\ShowAction;
use App\Http\Controllers\Admin\Actions\Event\StoreAction;
use App\Http\Controllers\Admin\Actions\Event\UpdateAction;
use App\Http\Requests\Admin\Event\IndexRequest;
use App\Http\Requests\Admin\Event\StoreRequest;
use App\Http\Requests\Admin\Event\UpdateRequest;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
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
     * イベント一覧表示
     */
    public function index(IndexRequest $request): View
    {
        return $this->indexAction->execute($request);
    }

    /**
     * イベント作成フォーム表示
     */
    public function create(Request $request): View
    {
        return $this->createAction->execute($request);
    }

    /**
     * イベント作成処理
     */
    public function store(StoreRequest $request)
    {
        return $this->storeAction->execute($request);
    }

    /**
     * イベント詳細表示
     */
    public function show(Event $event): View
    {
        return $this->showAction->execute($event);
    }

    /**
     * イベント編集フォーム表示
     */
    public function edit(Event $event): View
    {
        return $this->editAction->execute($event);
    }

    /**
     * イベント更新処理
     */
    public function update(UpdateRequest $request, Event $event)
    {
        return $this->updateAction->execute($request, $event);
    }

    /**
     * イベント削除処理
     */
    public function destroy(Event $event)
    {
        return $this->destroyAction->execute($event);
    }

    /**
     * イベント画像取得API
     */
    public function image(string $ulid): JsonResponse
    {
        return $this->imageAction->execute($ulid);
    }
}
