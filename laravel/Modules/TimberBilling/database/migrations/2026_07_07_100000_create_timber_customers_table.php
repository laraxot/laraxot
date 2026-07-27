<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\TimberBilling\Models\TimberAgent;
use Modules\TimberBilling\Models\TimberCustomer;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberCustomer::class;

    public function up(): void
    {
        $userClass = XotData::make()->getUserClass();

        $this->tableCreate(function (Blueprint $table) use ($userClass): void {
            $table->id();

            $table->string('code')->nullable()->unique();
            $table->string('business_name');
            $table->string('vat_number')->nullable();
            $table->string('tax_code')->nullable();
            $table->boolean('is_customer')->default(true);
            $table->boolean('is_supplier')->default(false);

            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->default('Italia');

            $table->string('sdi_recipient_code')->nullable();
            $table->string('sdi_admin_ref')->nullable();
            $table->string('pec')->nullable();

            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();

            $table->decimal('default_discount_percent', 5, 2)->nullable();
            $table->string('price_list_code')->nullable();
            $table->decimal('credit_limit', 12, 2)->nullable();
            $table->foreignIdFor(TimberAgent::class, 'agent_id')->nullable()->index();
            $table->string('payment_terms')->nullable();
            $table->string('iban')->nullable();
            $table->string('our_bank')->nullable();
            $table->string('carriage_code')->nullable();
            $table->string('vat_rate_code')->nullable();
            $table->string('intent_declaration_number')->nullable();
            $table->date('intent_declaration_date')->nullable();
            $table->boolean('send_documents_by_email')->default(false);
            $table->boolean('invoice_with_withholding')->default(false);
            $table->text('document_notice')->nullable();
            $table->text('document_note')->nullable();

            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);

            $this->updateTimestamps($table, hasSoftDeletes: true);

            $table->index('business_name');
            $table->index('vat_number');
        });
    }
};