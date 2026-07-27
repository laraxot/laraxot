<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Modules\TimberBilling\Models\TimberWoodSpecies;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberWoodSpecies::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });

        if (TimberWoodSpecies::query()->exists()) {
            return;
        }

        $species = config('timberbilling.timber.wood_species', []);
        if (! is_array($species)) {
            return;
        }

        $order = 0;
        foreach ($species as $code => $name) {
            DB::table((new TimberWoodSpecies())->getTable())->insert([
                'code' => (string) $code,
                'name' => is_string($name) ? $name : (string) $code,
                'sort_order' => $order++,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};
