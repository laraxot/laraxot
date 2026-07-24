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
            $table->uuid('public_id')->unique();
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('document_type')->default('unknown');
            $table->string('status')->default('draft');
            $table->decimal('confidence', 5, 2)->default(0);
            $table->json('extracted_payload')->nullable();
            $table->longText('raw_text')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('applied_at')->nullable();

            $table->index(['status', 'document_type']);

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
