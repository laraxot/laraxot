<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Timber\Models\TimberCustomer;
use Modules\Timber\Models\TimberDocument;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberDocument::class;

    public function up(): void
    {
        $userClass = XotData::make()->getUserClass();

        $this->tableCreate(function (Blueprint $table) use ($userClass): void {
            $table->id();
            $table->string('document_type_code')->index();
            $table->string('number')->nullable()->index();
            $table->date('document_date')->index();
            $table->foreignIdFor(TimberCustomer::class, 'customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status_code')->default('draft')->index();
            $table->string('reference')->nullable();
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('stock_posted_at')->nullable();
            // users è su una connection diversa (cross-DB): niente ->constrained(), vedi errore MySQL 1824
            $table->foreignIdFor($userClass, 'created_by_user_id')->nullable()->index();
            $table->unique(['document_type_code', 'number']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);
        });
    }
};
