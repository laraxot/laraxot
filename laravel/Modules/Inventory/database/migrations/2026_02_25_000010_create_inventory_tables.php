<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouses', static function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable()->unique();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_final_destination')->default(false);
            $table->timestamps();

            $table->index(['name']);
        });

        Schema::create('inventory_movements', static function (Blueprint $table): void {
            $table->id();

            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('from_warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            $table->foreignId('to_warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();

            $table->enum('movement_type', ['carico', 'scarico', 'trasferimento', 'reso']);
            $table->integer('quantity');
            $table->text('note')->nullable();

            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->nullOnDelete();
            $table->foreignId('intervention_id')->nullable()->constrained('interventions')->nullOnDelete();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->dateTime('occurred_at')->nullable();
            $table->boolean('origin_auto')->default(false);

            $table->timestamps();

            $table->index(['product_id', 'movement_type']);
            $table->index(['from_warehouse_id']);
            $table->index(['to_warehouse_id']);
            $table->index(['work_order_id']);
            $table->index(['intervention_id']);
            $table->index(['occurred_at']);
        });

        Schema::create('product_units', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('serial')->nullable()->unique();
            $table->foreignId('current_warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            $table->string('status')->default('available'); // available|reserved|consumed|scrapped
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['product_id']);
            $table->index(['current_warehouse_id']);
            $table->index(['status']);
        });

        Schema::create('inventory_movement_product_units', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('inventory_movement_id')->constrained('inventory_movements')->cascadeOnDelete();
            $table->foreignId('product_unit_id')->constrained('product_units')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['inventory_movement_id', 'product_unit_id'], 'inv_mov_unit_unique');
            $table->index(['product_unit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_movement_product_units');
        Schema::dropIfExists('product_units');
        Schema::dropIfExists('inventory_movements');
        Schema::dropIfExists('warehouses');
    }
};

