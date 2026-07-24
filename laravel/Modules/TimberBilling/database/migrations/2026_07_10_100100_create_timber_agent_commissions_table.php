<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('timber_agent_commissions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('agent_id')->constrained('timber_agents')->cascadeOnDelete();
            $table->string('scope_type')->default('default'); // default | category | product
            $table->string('category')->nullable();
            $table->foreignId('product_id')->nullable()->constrained('timber_products')->cascadeOnDelete();
            $table->decimal('rate_percent', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timber_agent_commissions');
    }
};
