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
            $table->foreignId('energy_broker_document_id')->constrained('energy_broker_documents')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->decimal('score', 5, 2)->default(0);
            $table->string('status')->default('needs_review');
            $table->json('matched_on')->nullable();
            $table->text('reason')->nullable();

            $this->updateTimestamps($table);
        });
    }
};
