<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\TimberBilling\Models\TimberEInvoice;
use Modules\TimberBilling\Models\TimberInvoice;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberEInvoice::class;

    public function up(): void
    {
        $userClass = XotData::make()->getUserClass();

        $this->tableCreate(function (Blueprint $table) use ($userClass): void {
            $table->id();
            $table->foreignIdFor(TimberInvoice::class, 'invoice_id')->constrained('timber_invoices')->cascadeOnDelete();
            $table->string('direction')->default('out')->index();
            $table->string('sdi_status')->default('da_inviare')->index();
            $table->string('sdi_identifier')->nullable();
            $table->string('file_name')->nullable();
            $table->longText('xml_content')->nullable();
            $table->timestamp('transmitted_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('error_message')->nullable();

            $this->updateTimestamps($table, hasSoftDeletes: true);

            $table->unique('invoice_id');
        });
    }

    public function down(): void
    {
        $this->dropTableIfExists('timber_e_invoices');
    }
};