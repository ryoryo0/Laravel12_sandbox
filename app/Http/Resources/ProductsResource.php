<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // 最初のバリアント（色・サイズ・価格情報）を取得
        $firstVariant = $this->variants->first();
        // APIレスポンスデータを成型
        // 商品
        $id = $this->id;
        $name = $this->name;
        $imageUrl = $this->getImageUrl($this->getThumbnail()); 
        $categories = $this->categories()->pluck("name", "categories.id")->toArray() ?? [];
        $isPickUp = $this->is_pick_up;
        $isNew = $this->isNew();
        $isEvent = $this->hasEvent();
        // 商品在庫
        $price = $firstVariant?->getDiscountedPrice() ?? null;
        $originalPrice = ($firstVariant && $isEvent) ? number_format($firstVariant?->price) : null;
        // イベント
        $discountLabelList = $this->getDiscountLabelList();

        return [
            // 商品
            'id' => $id,
            'name' => $name,
            'imageUrl' => $imageUrl,
            'categories' => $categories,
            'isPickUp' => $isPickUp,
            'isNew' => $isNew,
            'isEvent' => $isEvent,
            // 商品在庫
            'price' => $price,
            'originalPrice' => $originalPrice,
            // イベント
            'discountLabelList' => $discountLabelList,
        ];
    }



        
    /**
     * 画像パスを取得する
     *
     * @param  mixed $thumbnail
     * @return string
     */
    private function getImageUrl($thumbnail): string
    {
        $result = $thumbnail 
            ? config('app.url') . '/storage/' . $thumbnail->file_path
            : config('app.url') . '/assets/images/no-image.jpg';

        return $result;
    }


        
    /**
     * 商品の割引表示のリストを成型
     *
     * @return array
     */
    private function getDiscountLabelList(): array
    {
        // アクティブなイベントのみを取得
        $activeEvents = $this->events->filter(function ($event) {
            return $event->isActive();
        });

        // アクティブなイベントがない場合はnullを返す
        if ($activeEvents->isEmpty()) {
            return [];
        }

        // 各イベントの割引情報を配列に変換
        $discountLabelList = $activeEvents->map(function ($event) {
            // discount_typeに応じて割引テキストを生成
            $discountText = match ($event->discount_type) {
                'rate' => $event->discount_rate . '%',
                'amount' => $event->discount_amount . '円',
                default => '',
            };

            if (empty($discountText)) {
                return null;
            }

            $discountLabel = "{$event->name}:{$discountText}OFF";
            return $discountLabel;
        })->filter()->values()->toArray();

        return $discountLabelList;
    }
}
