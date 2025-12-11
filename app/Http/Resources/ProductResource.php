<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // 最初のバリアント（色・サイズ・価格情報）を取得
        $variant = $request->query('color') 
            ? $this->variants->where('color', $request->query('color'))->first() 
            : $this->variants->first();

        
        // APIレスポンスデータを成型 ▼
        // 商品に関する情報
        $id = $this->id;
        $name = $this->name;
        $imageUrl = $this->images->map(function($image){
            return config('app.url') . '/storage/' . $image->file_path;
        })->toArray();
        $categories = $this->categories()->pluck("name", "categories.id")->toArray() ?? [];
        $isPickUp = $this->is_pick_up;
        $isNew = $this->isNew();
        $isEvent = $this->hasEvent();
        $price = $variant?->getDiscountedPrice() ?? '0';
        $originalPrice = number_format($variant?->price) ?? 0;
        $discountLabelList = $this->getDiscountLabelList();

        // 在庫に関する情報
        $variants = $this->variants;
        $colorList = array_unique($variants->pluck('color')->toArray());
        $stockDate = $this->variants->where('color', $variant->color)
            ->map(function ($variant) {
                return [
                    'size' => $variant->size,
                    'stock' => $variant->stock,
                ];
            })->values();

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
            // 在庫に関する情報を取得
            'colorList' => $colorList,
            'stockDate' => $stockDate
        ];
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


