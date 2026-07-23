<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            if (Schema::hasColumn('users', 'customer_id')) {
                return;
            }

            $table
                ->foreignId('customer_id')
                ->nullable()
                ->after('email')
                ->constrained('customers')
                ->nullOnDelete();

            $table->index(['customer_id']);
        });
    }

    public function down(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'customer_id')) {
                return;
            }

            $table->dropConstrainedForeignId('customer_id');
        });
    }
};

