<?php

declare(strict_types=1);

namespace Modules\Auth\Actions;

use Spatie\QueueableAction\QueueableAction;

/**
 * Espande la gerarchia degli account: dato un responsabile restituisce se stesso
 * e tutti i collaboratori sotto di lui, a qualsiasi livello di profondita'.
 *
 * Logica pura: riceve la mappa responsabile -> collaboratori gia' caricata, cosi'
 * resta testabile e non fa una query per livello.
 */
final class ResolveTeamMemberIdsAction
{
    use QueueableAction;

    /**
     * @param  array<string, list<string>>  $subordinatesByManager  manager_id => [user_id, ...]
     * @return array<int, string> id del responsabile e di tutti i suoi collaboratori
     */
    public function execute(string $managerId, array $subordinatesByManager): array
    {
        /** @var array<string, true> $resolved */
        $resolved = [];
        $queue = [$managerId];

        while ($queue !== []) {
            $currentId = array_shift($queue);

            // Una gerarchia con un ciclo (A responsabile di B, B di A) manderebbe
            // in loop infinito: ogni id si visita una volta sola.
            if (isset($resolved[$currentId])) {
                continue;
            }

            $resolved[$currentId] = true;

            foreach ($subordinatesByManager[$currentId] ?? [] as $subordinateId) {
                if (! isset($resolved[$subordinateId])) {
                    $queue[] = $subordinateId;
                }
            }
        }

        return array_keys($resolved);
    }
}
