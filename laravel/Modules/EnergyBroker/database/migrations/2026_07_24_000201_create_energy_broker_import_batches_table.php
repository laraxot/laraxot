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
            $table->foreignId('uploaded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('source_name');
            $table->string('source_type')->default('manual_upload');
            $table->string('status')->default('draft');
            $table->unsignedInteger('total_files')->default(0);
            $table->unsignedInteger('processed_files')->default(0);
            $table->json('meta')->nullable();

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
