<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Modules\User\Models\User;
use Illuminate\Support\Collection;
use Spatie\QueueableAction\QueueableAction;

/**
 * Turns the names an operator says out loud ("eravamo io e Marco") into user
 * ids that can be attached to the intervention.
 *
 * Attaching the wrong person to a job is worse than attaching nobody: it moves
 * someone else's hours onto a customer document. So the matching is
 * deliberately timid — a name is resolved only when there is exactly one
 * candidate, and an ambiguous first name ("Marco" with two Marcos on the
 * payroll) is dropped for the operator to fix by hand.
 */
final class ResolveDictatedOperatorsAction
{
    use QueueableAction;

    /**
     * @param  list<string>  $names
     * @return list<int>
     */
    public function execute(array $names): array
    {
        $ids = [];

        foreach ($names as $name) {
            $needle = mb_strtolower(trim($name));
            if ($needle === '') {
                continue;
            }

            $id = $this->resolve($needle);
            if ($id !== null && ! in_array($id, $ids, true)) {
                $ids[] = $id;
            }
        }

        return $ids;
    }

    private function resolve(string $needle): ?int
    {
        $exact = $this->uniqueId(
            User::query()->whereRaw('LOWER(name) = ?', [$needle])->limit(2)->pluck('id'),
        );

        if ($exact !== null) {
            return $exact;
        }

        // Voice notes use first names. Anchoring on the start of the full name
        // keeps "Marco" from matching "Gianmarco", and the uniqueness check
        // below throws away the cases where it is genuinely ambiguous.
        return $this->uniqueId(
            User::query()
                ->whereRaw('LOWER(name) LIKE ?', [$needle.' %'])
                ->limit(2)
                ->pluck('id'),
        );
    }

    /**
     * @param  Collection<int, mixed>  $ids
     */
    private function uniqueId(Collection $ids): ?int
    {
        return $ids->count() === 1 ? (int) $ids->first() : null;
    }
}
