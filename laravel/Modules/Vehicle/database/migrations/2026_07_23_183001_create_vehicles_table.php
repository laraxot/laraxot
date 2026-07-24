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
            $table->uuid('uuid')->nullable()->unique();
            $table->string('plate_number')->unique();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->unsignedInteger('year')->nullable();
            $table->unsignedInteger('odometer_km')->nullable();
            $table->date('last_service_on')->nullable();
            $table->date('insurance_expires_on')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);
        });
    }
};
