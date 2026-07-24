<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Unit;

use Modules\Auth\Tests\TestCase;

uses(TestCase::class);

use Modules\Auth\Actions\ResolveTeamMemberIdsAction;

// Nota: ResolveTeamMemberIdsAction::execute() nel modulo reshapato usa id stringa
// (UUID di Modules\User\Models\User) invece degli interi del sorgente originale;
// qui si usano stringhe non numeriche per evitare la conversione automatica delle
// chiavi array PHP da stringa numerica a intero.
it('returns only the manager when nobody reports to them', function (): void {
    $ids = (new ResolveTeamMemberIdsAction())->execute('m7', []);

    expect($ids)->toBe(['m7']);
});

it('includes direct collaborators', function (): void {
    $ids = (new ResolveTeamMemberIdsAction())->execute('u1', ['u1' => ['u2', 'u3']]);

    expect($ids)->toBe(['u1', 'u2', 'u3']);
});

it('walks the hierarchy down to any depth', function (): void {
    // u1 -> u2 -> u4, e u1 -> u3: il capo vede tutti, anche i collaboratori dei collaboratori.
    $ids = (new ResolveTeamMemberIdsAction())->execute('u1', [
        'u1' => ['u2', 'u3'],
        'u2' => ['u4'],
        'u4' => ['u5'],
    ]);

    expect($ids)->toBe(['u1', 'u2', 'u3', 'u4', 'u5']);
});

it('returns only the subtree of the given manager', function (): void {
    $ids = (new ResolveTeamMemberIdsAction())->execute('u2', [
        'u1' => ['u2', 'u3'],
        'u2' => ['u4'],
    ]);

    expect($ids)->toBe(['u2', 'u4']);
});

it('does not loop forever on a circular hierarchy', function (): void {
    // Configurazione sbagliata ma possibile: A responsabile di B e B di A.
    $ids = (new ResolveTeamMemberIdsAction())->execute('u1', [
        'u1' => ['u2'],
        'u2' => ['u1'],
    ]);

    expect($ids)->toBe(['u1', 'u2']);
});

it('does not duplicate a collaborator reachable by two paths', function (): void {
    $ids = (new ResolveTeamMemberIdsAction())->execute('u1', [
        'u1' => ['u2', 'u3'],
        'u2' => ['u4'],
        'u3' => ['u4'],
    ]);

    expect($ids)->toBe(['u1', 'u2', 'u3', 'u4']);
});
