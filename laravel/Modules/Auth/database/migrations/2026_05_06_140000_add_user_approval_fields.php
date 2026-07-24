<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->timestamp('approved_at')->nullable()->after('email_verified_at');
            // users.id e' string(36) UUID: la colonna deve avere lo stesso tipo,
            // altrimenti il vincolo di foreign key fallisce (o non e' realmente
            // applicato, a seconda del driver).
            $table->foreignUuid('approved_by_user_id')->nullable()->after('approved_at')->constrained('users')->nullOnDelete();
        });

        DB::table('users')->update([
            'approved_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropForeign(['approved_by_user_id']);
            $table->dropColumn(['approved_by_user_id', 'approved_at']);
        });
    }
};
