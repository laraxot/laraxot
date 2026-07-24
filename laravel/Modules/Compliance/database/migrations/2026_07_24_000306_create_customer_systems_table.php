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
            $table->string('code')->nullable()->unique();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('customer_site_id')->nullable()->constrained('customer_sites')->nullOnDelete();

            $table->string('system_type'); // video_surveillance | electrical_panel | photovoltaic
            $table->string('description')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->decimal('power_kw', 8, 2)->nullable(); // impianti fotovoltaici: potenza di picco
            $table->date('installation_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();

            $table->index(['customer_id']);
            $table->index(['system_type']);

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
