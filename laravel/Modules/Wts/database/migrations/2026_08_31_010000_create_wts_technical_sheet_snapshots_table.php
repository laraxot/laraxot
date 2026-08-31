<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Wts\Models\WtsTechnicalSheetSnapshot;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = WtsTechnicalSheetSnapshot::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('legacy_system')->default('legacy');
            $table->string('sheet_code')->unique();
            $table->string('producer_name');
            $table->string('waste_code');
            $table->string('hp_codes')->nullable();
            $table->string('packaging_description')->nullable();
            $table->string('production_description')->nullable();
            $table->text('note_1')->nullable();
            $table->text('note_2')->nullable();
            $table->json('legacy_payload')->nullable();
            $table->timestamps();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table);
        });
    }
};
