<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        // Lo storico stampe e' un log: la stessa etichetta puo' essere ristampata
        // piu' volte (ogni tappa/spostamento), quindi label_code non e' univoco.
        Schema::table('timber_label_prints', function (Blueprint $table): void {
            $table->dropUnique('timber_label_prints_label_code_unique');
            $table->index('label_code');
        });
    }

    public function down(): void
    {
        Schema::table('timber_label_prints', function (Blueprint $table): void {
            $table->dropIndex(['label_code']);
            $table->unique('label_code');
        });
    }
};
