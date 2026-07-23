<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', static function (Blueprint $table): void {
            $table->string('electronic_invoice_provider')->nullable()->after('notes');
            $table->string('electronic_invoice_external_id')->nullable()->after('electronic_invoice_provider');
            $table->timestamp('electronic_invoice_sent_at')->nullable()->after('electronic_invoice_external_id');
            $table->text('electronic_invoice_last_error')->nullable()->after('electronic_invoice_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', static function (Blueprint $table): void {
            $table->dropColumn([
                'electronic_invoice_provider',
                'electronic_invoice_external_id',
                'electronic_invoice_sent_at',
                'electronic_invoice_last_error',
            ]);
        });
    }
};

