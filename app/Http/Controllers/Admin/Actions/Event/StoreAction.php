<?php

namespace App\Http\Controllers\Admin\Actions\Event;

use App\Http\Requests\Admin\Event\StoreRequest;
use App\Models\Event;
use App\Models\TemporaryImage;
use App\Services\File\FileTransferService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class StoreAction
{
    public function __construct(
        private FileTransferService $fileTransferService
    ) {}

    public function execute(StoreRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['create_admin_id'] = Auth::user()->id;

            DB::transaction(function () use ($validated) {
                // イベント登録
                $event = Event::create($validated);

                // イベントと商品の紐付け
                if (!empty($validated['product_ids'])) {
                    $event->products()->sync($validated['product_ids']);
                }

                // イベント画像登録
                if (!empty($validated['thumbnail'])) {
                    $thumbImage = TemporaryImage::query()
                        ->where('ulid', $validated['thumbnail'])
                        ->first()
                        ->toArray();
                    $thumbImage['file_path'] = $this->fileTransferService->copyFileToDirectory($thumbImage['file_path'], 'event');
                    $event->images()->create($thumbImage);
                }

                Log::info('event create', ['event_id' => $event->id]);
            });

            return redirect()->route('admin.event.index')->with('success', 'イベントの登録が完了しました');
        } catch (Throwable $e) {
            Log::error($e);
            return redirect()->back()->with('error', 'イベントの登録に失敗しました');
        }
    }
}
