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
            $table->string('code')->nullable()->unique();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('customer_site_id')->nullable()->constrained('customer_sites')->nullOnDelete();

            $table->string('description')->nullable();
            $table->string('fuel_type')->nullable(); // metano | gpl | gasolio | biomassa | altro
            $table->decimal('total_power_kw', 8, 2)->nullable();
            $table->string('plant_registry_code')->nullable(); // codice catasto / libretto impianto
            $table->date('installation_date')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);

            $table->index(['customer_id']);

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
