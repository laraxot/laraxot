<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', static function (Blueprint $table): void {
            $table->id();

            $table->morphs('mediable');
            $table->foreignId('uploaded_by_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('kind'); // document|photo|signature|sketch|other
            $table->string('title')->nullable();

            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->json('meta')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['kind']);
        });

        Schema::create('signatures', static function (Blueprint $table): void {
            $table->id();
            $table->morphs('signable');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->dateTime('signed_at')->nullable();
            $table->json('payload')->nullable(); // optional raw capture data
            $table->timestamps();
        });

        Schema::create('sketches', static function (Blueprint $table): void {
            $table->id();
            $table->morphs('sketchable');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sketches');
        Schema::dropIfExists('signatures');
        Schema::dropIfExists('media');
    }
};

