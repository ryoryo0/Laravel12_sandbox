<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('イベント名');
            $table->text('description')->nullable()->comment('説明文');
            $table->enum('discount_type', ['rate', 'amount'])->comment('割引タイプ(rate:割引率, amount:割引額)');
            $table->decimal('discount_rate', 5, 2)->nullable()->comment('割引率(%)');
            $table->decimal('discount_amount', 10, 2)->nullable()->comment('割引額(¥)');
            $table->dateTime('start_date')->comment('開始日時');
            $table->dateTime('end_date')->comment('終了日時');
            $table->boolean('is_active')->default(true)->comment('有効・無効');
            $table->foreignId('create_admin_id')->constrained('admins')->comment('イベント作成者');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
