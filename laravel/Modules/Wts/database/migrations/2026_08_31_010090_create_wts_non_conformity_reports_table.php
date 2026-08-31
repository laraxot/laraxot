<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Wts\Models\WtsNonConformityReport;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = WtsNonConformityReport::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignId('wts_inbound_lot_id')->nullable()->constrained('wts_inbound_lots')->nullOnDelete();
            $table->foreignId('wts_inbound_record_id')->nullable()->constrained('wts_inbound_records')->nullOnDelete();
            $table->string('form_number')->nullable()->index();
            $table->text('note');
            $table->string('recipient_email')->nullable();
            // L'id utente e' un UUID di 36 caratteri, non un intero: un foreignId qui
            // troncherebbe il valore a 0 e perderebbe silenziosamente l'attribuzione.
            $table->string('reported_by_user_id', 36)->nullable();
            $table->timestamp('reported_at');
            $table->timestamp('email_sent_at')->nullable();
            $table->timestamps();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
