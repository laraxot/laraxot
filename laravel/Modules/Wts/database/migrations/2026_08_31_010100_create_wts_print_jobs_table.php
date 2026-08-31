<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Wts\Models\WtsPrintJob;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = WtsPrintJob::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            // Collo di riferimento (solo tracciabilita': il job resta valido anche se il collo sparisce).
            $table->foreignId('wts_unit_id')->nullable()->constrained('wts_units')->nullOnDelete();
            $table->string('label_code')->nullable();
            $table->longText('zpl');
            $table->unsignedInteger('copies')->default(1);
            // pending -> printed | failed
            $table->string('status_code')->default('pending')->index();
            $table->unsignedInteger('attempts')->default(0);
            $table->text('error')->nullable();
            $table->timestamp('printed_at')->nullable();
            $table->timestamps();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
