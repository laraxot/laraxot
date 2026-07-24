<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Gerarchia fra account: ogni collaboratore puo' avere un responsabile.
 * Serve a far vedere a un responsabile il lavoro dei propri collaboratori senza
 * dargli permessi da amministratore.
 */
return new class() extends Migration
{
    public function up(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            // users.id e' string(36) UUID: la colonna manager_id deve avere lo stesso
            // tipo, altrimenti il vincolo di foreign key fallisce (o non e' realmente
            // applicato, a seconda del driver).
            $table->foreignUuid('manager_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            $table->dropForeign(['manager_id']);
            $table->dropColumn('manager_id');
        });
    }
};
