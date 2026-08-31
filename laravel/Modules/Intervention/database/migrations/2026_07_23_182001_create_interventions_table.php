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
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            // L'id utente e' un UUID di 36 caratteri: una colonna intera lo troncherebbe
            // a 0, perdendo in silenzio chi ha eseguito l'operazione.
            $table->string('performed_by_user_id', 36)->nullable()->index();
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->unsignedInteger('travel_km')->nullable();
            $table->unsignedInteger('travel_minutes')->default(0);
            $table->unsignedInteger('work_minutes')->default(0);
            $table->decimal('amount', 10, 2)->nullable();
            $table->decimal('callout_fee', 10, 2)->default(0);
            $table->decimal('materials_total', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('report_payment_session_id')->nullable();
            $table->timestamp('report_paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->longText('notes_html')->nullable();
            $table->string('google_event_id')->nullable()->index();
            $table->string('google_calendar_id')->nullable();
            $table->string('google_sync_status')->nullable();
            $table->timestamp('google_synced_at')->nullable();
            $table->string('outcome_code')->nullable();
            $table->dateTime('validated_at')->nullable();
            $table->string('validated_by_user_id', 36)->nullable()->index();
            $table->index(['work_order_id']);
            $table->index(['customer_id']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);
        });
    }
};
