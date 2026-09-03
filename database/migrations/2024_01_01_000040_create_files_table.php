<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('bucket_name', 50);
            $table->text('storage_path');
            $table->string('original_name', 255);
            $table->string('mime_type', 100);
            $table->bigInteger('size_bytes');
            $table->uuid('uploaded_by')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
