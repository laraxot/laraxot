<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::table('timber_packaged_units', function (Blueprint $table): void {
            $table->string('article_code')->nullable()->after('package_type_code')->index();
            $table->string('traceable_code')->nullable()->after('article_code')->index();
        });
    }

    public function down(): void
    {
        Schema::table('timber_packaged_units', function (Blueprint $table): void {
            $table->dropColumn(['article_code', 'traceable_code']);
        });
    }
};
