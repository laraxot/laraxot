<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    /**
     * Table name following Laraxot philosophy.
     */
    protected ?string $table_name = 'positions';

    /**
     * Run the migration following Employee module naming standards.
     */
    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();

            $table->string('title');

            $table->text('description')->nullable();

            $table->string('department')->nullable();

            $table->unsignedInteger('level')->nullable();

            $table->boolean('is_active')->default(true);

            $table->index(['is_active'], 'positions_is_active_idx');
            $table->index(['department'], 'positions_department_idx');
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);
        });
    }
};
