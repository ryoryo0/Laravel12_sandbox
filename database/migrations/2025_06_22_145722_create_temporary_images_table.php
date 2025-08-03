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
        Schema::create('temporary_images', function (Blueprint $table) {
            $table->id();
            $table->string('original_filename')->comment('ファイル名');
            $table->string('stored_filename')->comment('ファイル実体の識別');
            $table->ulid('ulid')->comment('ulid');
            $table->string('file_path')->comment('画像パス');
            $table->string('file_size')->comment('ファイルサイズ');
            $table->string('file_extension')->comment('拡張子');
            $table->string('mime_type')->comment('MIMEタイプ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_images');
    }
};
