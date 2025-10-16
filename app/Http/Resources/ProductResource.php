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
        // サムネイル画像を取得
        $thumbnail = $this->thumbnail();

        // 最初のバリアント（色・サイズ・価格情報）を取得
        $firstVariant = $this->variants->first();

        // セール情報の計算（仮の実装 - 必要に応じて調整）
        $originalPrice = null;
        $discount = null;
        $badge = null;

        // is_pick_upがtrueの場合、NEWバッジを表示
        if ($this->is_pick_up) {
            $badge = 'new';
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'color' => $firstVariant?->color ?? 'カラー情報なし',
            'price' => $firstVariant?->price ?? 0,
            'originalPrice' => $originalPrice,
            'imageUrl' => $thumbnail?->path ?? '/images/no-image.jpg',
            'badge' => $badge,
            'discount' => $discount,
            'code' => $this->code,
            'isPublic' => (bool) $this->is_public,
            'isPickUp' => (bool) $this->is_pick_up,
        ];
    }
}
