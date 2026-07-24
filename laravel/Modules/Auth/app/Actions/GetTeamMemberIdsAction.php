<?php

declare(strict_types=1);

namespace Modules\Auth\Actions;

use Modules\User\Models\User;
use Spatie\QueueableAction\QueueableAction;

/**
 * Id del responsabile e di tutti i suoi collaboratori, a qualsiasi profondita'.
 */
final class GetTeamMemberIdsAction
{
    use QueueableAction;

    public function __construct(
        private ResolveTeamMemberIdsAction $resolveTeamMemberIds,
    ) {}

    /**
     * @return array<int, string>
     */
    public function execute(User $manager): array
    {
        // Una sola query: la mappa completa costa meno di una query per livello.
        /** @var array<string, list<string>> $subordinatesByManager */
        $subordinatesByManager = [];

        User::query()
            ->whereNotNull('manager_id')
            ->get(['id', 'manager_id'])
            ->each(static function (User $user) use (&$subordinatesByManager): void {
                $managerId = $user->getAttribute('manager_id');
                if (! is_string($managerId)) {
                    return;
                }
                $subordinatesByManager[$managerId][] = (string) $user->id;
            });

        return $this->resolveTeamMemberIds->execute((string) $manager->id, $subordinatesByManager);
    }
}
