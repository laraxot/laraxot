<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('articles', static function (Blueprint $table): void {
            if (! Schema::hasColumn('articles', 'content_type')) {
                $table->string('content_type')->default('post');
            }
        });
    }

    public function down(): void
    {
        Schema::table('articles', static function (Blueprint $table): void {
            if (Schema::hasColumn('articles', 'content_type')) {
                $table->dropColumn('content_type');
            }
        });
    }
};
