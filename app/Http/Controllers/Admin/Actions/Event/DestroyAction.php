<?php

namespace App\Http\Controllers\Admin\Actions\Event;

use App\Models\Event;
use App\Services\File\FileTransferService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class DestroyAction
{
    public function __construct(
        private FileTransferService $fileTransferService
    ) {}

    public function execute(Event $event)
    {
        try {
            $adminUser = Auth::user();

            // 権限チェック：現在のadminユーザーが作成したイベントかをチェック
            if ($event->create_admin_id !== $adminUser->id) {
                return redirect()->route('admin.event.index')->with('error', '削除権限がないか、イベントが存在しません。');
            }

            DB::transaction(function () use ($event) {
                // イベントに関連する画像ファイルを削除
                $image = $event->image;
                if ($image && $image->file_path) {
                    $this->fileTransferService->deleteFile($image->file_path);
                }

                // イベントの画像データを削除
                $event->image()?->delete();

                // イベントと商品の関連を削除
                $event->products()->detach();

                // イベントを削除
                $event->delete();

                Log::info('Event deleted', ['event_id' => $event->id, 'admin_id' => Auth::user()->id]);
            });

            return redirect()->route('admin.event.index')->with('success', 'イベント「' . $event->name . '」を削除しました。');
        } catch (Throwable $e) {
            Log::error('Event deletion failed', ['error' => $e->getMessage(), 'event_id' => $event->id]);
            return redirect()->route('admin.event.index')->with('error', 'イベントの削除に失敗しました。');
        }
    }
}
