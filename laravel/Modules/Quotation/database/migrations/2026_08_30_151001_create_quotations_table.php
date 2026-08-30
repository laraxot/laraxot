<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Quotation\Models\Quotation;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = Quotation::class;

    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('source_quotation_id')->nullable()->index();
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->nullOnDelete();

            $table->string('code')->nullable()->unique();
            $table->string('status_code')->default('draft');
            $table->string('title')->nullable();
            $table->text('notes')->nullable();
            $table->text('voice_transcript')->nullable();
            $table->json('voice_ai_response')->nullable();
            $table->json('technical_params')->nullable();

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('taxable_amount', 12, 2)->default(0);
            $table->decimal('tax_percent', 5, 2)->default(22);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('cost_total', 12, 2)->default(0);

            $table->dateTime('issued_at')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->dateTime('delivery_at')->nullable();
            $table->unsignedInteger('lead_time_days')->nullable();

            $table->index(['customer_id']);
            $table->index(['work_order_id']);
            $table->index(['status_code']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);

            foreach ([
                'project' => static fn (Blueprint $t) => $t->string('project')->nullable(),
                'cost_total' => static fn (Blueprint $t) => $t->decimal('cost_total', 12, 2)->default(0),
                'delivery_at' => static fn (Blueprint $t) => $t->dateTime('delivery_at')->nullable(),
                'lead_time_days' => static fn (Blueprint $t) => $t->unsignedInteger('lead_time_days')->nullable(),
            ] as $column => $add) {
                if (! $this->hasColumn($column)) {
                    $add($table);
                }
            }
        });
    }
};
