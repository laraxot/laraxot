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
            $table->string('business_name');
            $table->string('vat_number')->nullable();
            $table->string('tax_code')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('status')->default('active');
            $table->text('notes')->nullable();

            $table->index(['business_name']);
            $table->index(['status']);

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
