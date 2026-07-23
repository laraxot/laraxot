<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_medical_visits', static function (Blueprint $table): void {
            $table->foreignId('media_id')->nullable()->after('employee_id')->constrained('media')->nullOnDelete();
            $table->string('title')->nullable()->after('media_id');
        });

        Schema::table('employee_trainings', static function (Blueprint $table): void {
            $table->foreignId('media_id')->nullable()->after('employee_id')->constrained('media')->nullOnDelete();
            $table->string('title')->nullable()->after('media_id');
        });
    }

    public function down(): void
    {
        Schema::table('employee_trainings', static function (Blueprint $table): void {
            $table->dropConstrainedForeignId('media_id');
            $table->dropColumn(['title']);
        });

        Schema::table('employee_medical_visits', static function (Blueprint $table): void {
            $table->dropConstrainedForeignId('media_id');
            $table->dropColumn(['title']);
        });
    }
};

