<?php

declare(strict_types=1);

namespace Modules\Auth\Actions;

use App\Models\User;
use Modules\Auth\Actions\ResolveTeamMemberIdsAction;

/**
 * Id del responsabile e di tutti i suoi collaboratori, a qualsiasi profondita'.
 */
final readonly class GetTeamMemberIdsAction
{
    public function __construct(
        private ResolveTeamMemberIdsAction $resolveTeamMemberIds,
    ) {}

    /**
     * @return array<int, int>
     */
    public function execute(User $manager): array
    {
        // Una sola query: la mappa completa costa meno di una query per livello.
        $subordinatesByManager = [];

        User::query()
            ->whereNotNull('manager_id')
            ->get(['id', 'manager_id'])
            ->each(static function (User $user) use (&$subordinatesByManager): void {
                $subordinatesByManager[(int) $user->manager_id][] = $user->id;
            });

        return $this->resolveTeamMemberIds->execute($manager->id, $subordinatesByManager);
    }
}
