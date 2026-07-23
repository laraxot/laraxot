<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotation_templates', static function (Blueprint $table): void {
            $table->id();
            $table->string('title')->unique();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('quotation_template_items', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('quotation_template_id')->constrained('quotation_templates')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();

            $table->string('description');
            $table->decimal('quantity', 12, 2)->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('discount_percent', 12, 2)->default(0);
            $table->decimal('line_total', 12, 2)->default(0);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['quotation_template_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotation_template_items');
        Schema::dropIfExists('quotation_templates');
    }
};

