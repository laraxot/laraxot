<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table): void {
            // The quotation this one was based on ("come quello di X"): lets the
            // operator re-prepare the AI-rewritten attachment later.
            $table->unsignedBigInteger('source_quotation_id')->nullable()->after('customer_id');
            $table->index('source_quotation_id');
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table): void {
            $table->dropIndex(['source_quotation_id']);
            $table->dropColumn('source_quotation_id');
        });
    }
};
