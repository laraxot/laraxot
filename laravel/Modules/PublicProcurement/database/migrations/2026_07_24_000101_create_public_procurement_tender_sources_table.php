<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('source_type')->default('manual');
            $table->string('url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('filters')->nullable();
            $table->text('notes')->nullable();
            $table->index(['source_type', 'is_active']);

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
