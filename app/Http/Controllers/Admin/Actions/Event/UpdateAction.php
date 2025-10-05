<?php

namespace App\Http\Controllers\Admin\Actions\Event;

use App\Http\Requests\Admin\Event\UpdateRequest;
use App\Models\Event;
use App\Models\TemporaryImage;
use App\Services\File\FileTransferService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateAction
{
    public function __construct(
        private FileTransferService $fileTransferService
    ) {}

    public function execute(UpdateRequest $request, Event $event)
    {
        try {
            $validated = $request->validated();

            DB::transaction(function () use ($validated, $event) {
                // イベント更新
                $event->update($validated);

                // イベントと商品の紐付け更新
                if (isset($validated['product_ids'])) {
                    $event->products()->sync($validated['product_ids']);
                }

                // イベント画像更新
                if (!empty($validated['thumbnail'])) {
                    $event->images()->delete();
                    $thumbImage = TemporaryImage::query()
                        ->where('ulid', $validated['thumbnail'])
                        ->first()
                        ->toArray();
                    $thumbImage['file_path'] = $this->fileTransferService->copyFileToDirectory($thumbImage['file_path'], 'event');
                    $event->images()->create($thumbImage);
                }

                Log::info('event update', ['event_id' => $event->id]);
            });

            return redirect()->route('admin.event.index')->with('success', 'イベント「' . $validated['name'] . '」の更新が完了しました');
        } catch (Throwable $e) {
            Log::error($e);
            return redirect()->back()->with('error', 'イベントの更新に失敗しました');
        }
    }
}
