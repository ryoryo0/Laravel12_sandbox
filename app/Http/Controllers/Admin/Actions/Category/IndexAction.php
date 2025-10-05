<?php

namespace App\Http\Controllers\Admin\Actions\Category;

use App\Http\Requests\Admin\Category\IndexRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class IndexAction
{
    public function execute(IndexRequest $request): View
    {
        $adminUser = Auth::user();

        // カテゴリー検索クエリ
        $query = Category::where('create_admin_id', $adminUser->id);

        // 名前検索
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        // ページネーション
        $categories = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.category.index', compact('categories'));
    }
}
