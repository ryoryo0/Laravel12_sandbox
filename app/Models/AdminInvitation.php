<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AdminInvitation extends Model
{
    protected $fillable = [
        'email',
        'token',
        'expires_at',
        'used_at',
        'admin_id',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    /**
     * 招待を送った管理者とのリレーション
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * 有効な招待のみを取得するスコープ
     */
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now())
                    ->whereNull('used_at');
    }

    /**
     * 期限切れの招待を取得するスコープ
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * 未使用の招待を取得するスコープ
     */
    public function scopeUnused($query)
    {
        return $query->whereNull('used_at');
    }

    /**
     * トークンが有効かどうかを確認
     */
    public function isValid(): bool
    {
        return $this->expires_at->isFuture() && is_null($this->used_at);
    }

    /**
     * 招待を使用済みにマーク
     */
    public function markAsUsed(): void
    {
        $this->update(['used_at' => now()]);
    }

    /**
     * 新しいトークンを生成
     */
    public static function generateToken(): string
    {
        return Str::random(64);
    }
}
