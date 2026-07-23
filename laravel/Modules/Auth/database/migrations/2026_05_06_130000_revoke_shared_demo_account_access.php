<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $emails = [
            'demo@cavalliniservice.com',
        ];

        $userIds = DB::table('users')
            ->whereIn('email', $emails)
            ->pluck('id')
            ->map(fn (mixed $id): int => (int) $id)
            ->all();

        if ($userIds === []) {
            return;
        }

        DB::table('model_has_roles')
            ->where('model_type', 'App\\Models\\User')
            ->whereIn('model_id', $userIds)
            ->delete();

        DB::table('users')
            ->whereIn('id', $userIds)
            ->update([
                'password' => Hash::make(Str::random(64)),
                'remember_token' => null,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Accesso condiviso demo rimosso intenzionalmente: non viene ripristinato.
    }
};
