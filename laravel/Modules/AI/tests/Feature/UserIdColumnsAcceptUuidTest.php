<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Modules\AI\Tests\TestCase;

/*
 * In questa base l'id utente e' un UUID di 36 caratteri. Una colonna intera lo
 * tronca a 0 senza lamentarsi: la riga resta legata a nessuno, o all'utente
 * sbagliato, e nessuno se ne accorge finche' non serve sapere chi ha fatto cosa.
 *
 * Il test guarda lo schema e non un singolo salvataggio, perche' l'invariante e'
 * della tabella: su SQLite un insert passerebbe comunque, il tipo non e' imposto.
 *
 * Le colonne stanno in un unico test e non in un dataset: ogni riga di dataset
 * fa ripartire l'applicazione, e su una suite intera si pagano minuti per una
 * verifica che costa millisecondi.
 */
uses(TestCase::class);

it('stores user ids as strings, not integers', function (): void {
    $columns = [
        ['ai_threads', 'created_by_user_id'],
        ['ai_action_proposals', 'proposed_by_user_id'],
        ['ai_action_proposals', 'confirmed_by_user_id'],
        ['ai_messages', 'user_id'],
        ['ai_tool_logs', 'user_id'],
    ];

    $numeric = [];
    foreach ($columns as [$table, $column]) {
        expect(Schema::hasColumn($table, $column))->toBeTrue("La colonna {$table}.{$column} non esiste");

        if (! in_array(Schema::getColumnType($table, $column), ['string', 'varchar', 'text'], true)) {
            $numeric[] = $table.'.'.$column;
        }
    }

    expect($numeric)->toBe([], 'Colonne numeriche: un id UUID viene troncato');
});
