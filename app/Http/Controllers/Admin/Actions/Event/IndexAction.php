<?php

namespace App\Http\Controllers\Admin\Actions\Event;

use App\Http\Requests\Admin\Event\IndexRequest;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class IndexAction
{
    const PAGINATE = 15;

    public function execute(IndexRequest $request): View
    {
        $adminUser = Auth::user();

        $query = $adminUser->events();
        $params = $request;
        $this->getQuery($query, $params);
        $events = $query->paginate(self::PAGINATE);
        $events->appends($request->query());
        $bulkActions = $this->getBulkActionLabels();
        $headings = $this->getHeadingLabels();

        return view('admin.event.index')
            ->with([
                'events' => $events,
                'bulkActions' => $bulkActions,
                'headings'  => $headings,
            ]);
    }

    /**
     * 一括操作セレクトラベルとkeyの取得
     */
    private function getBulkActionLabels(): array
    {
        return [
            'bulk_delete' => 'チェック項目を一括削除',
            'bulk_deactivate' => 'チェック項目を一括無効化',
        ];
    }

    /**
     * テーブルの見出しを取得
     */
    private function getHeadingLabels(): array
    {
        return [
            'イベント名',
            '割引内容',
            '開始日時',
            '終了日時',
            '状態',
            '作成日',
            '操作',
        ];
    }

    /**
     * クエリビルダーに絞り込み
     */
    private function getQuery(Builder $query, $params): void
    {
        if ($params->input('name')) {
            $query->where('name', 'like', '%' . $params->input('name') . '%');
        }

        if ($params->input('is_active') !== null) {
            $query->where('is_active', $params->input('is_active'));
        }

        if ($params->input('discount_type')) {
            $query->where('discount_type', $params->input('discount_type'));
        }
    }
}
