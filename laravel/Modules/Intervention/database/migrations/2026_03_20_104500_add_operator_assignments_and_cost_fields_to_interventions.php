<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interventions', static function (Blueprint $table): void {
            $table->decimal('callout_fee', 10, 2)->default(0)->after('amount');
            $table->decimal('materials_total', 10, 2)->default(0)->after('callout_fee');
            $table->decimal('total_amount', 10, 2)->default(0)->after('materials_total');
        });

        Schema::create('intervention_user_assignments', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('intervention_id')->constrained('interventions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['intervention_id', 'user_id']);
            $table->index(['user_id']);
        });

        Schema::create('intervention_material_lines', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('intervention_id')->constrained('interventions')->cascadeOnDelete();
            $table->string('description');
            $table->decimal('quantity', 10, 2)->default(1);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('line_total', 10, 2)->default(0);
            $table->timestamps();

            $table->index(['intervention_id']);
            $table->index(['description']);
        });

        DB::table('interventions')
            ->select(['id', 'performed_by_user_id'])
            ->whereNotNull('performed_by_user_id')
            ->orderBy('id')
            ->chunkById(250, static function ($rows): void {
                $now = now();

                $payload = collect($rows)
                    ->map(static fn ($row): array => [
                        'intervention_id' => (int) $row->id,
                        'user_id' => (int) $row->performed_by_user_id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])
                    ->all();

                if ($payload !== []) {
                    DB::table('intervention_user_assignments')->insertOrIgnore($payload);
                }
            }, 'id');
    }

    public function down(): void
    {
        Schema::dropIfExists('intervention_material_lines');
        Schema::dropIfExists('intervention_user_assignments');

        Schema::table('interventions', static function (Blueprint $table): void {
            $table->dropColumn(['callout_fee', 'materials_total', 'total_amount']);
        });
    }
};
