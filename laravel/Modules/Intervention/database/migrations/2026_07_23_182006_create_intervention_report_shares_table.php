<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('intervention_id')->constrained('interventions')->cascadeOnDelete();
            $table->string('token', 64)->unique();
            $table->unsignedBigInteger('created_by_user_id')->nullable()->index();
            $table->dateTime('expires_at')->nullable();
            $table->index(['intervention_id']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
