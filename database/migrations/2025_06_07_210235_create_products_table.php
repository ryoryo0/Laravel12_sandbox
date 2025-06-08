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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('商品名');
            $table->string('description')->nullable()->comment('説明文');
            $table->foreignId('category_id')->constrained('product_categories')->comment('商品のカテゴリーID');
            $table->foreignId('create_admin_id')->constrained('admins')->comment('商品作成者');
            $table->ulid('ulid')->comment('商品コード');
            $table->boolean('is_public')->comment('公開・非公開');
            $table->boolean('is_pick_up')->comment('おすすめ');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
