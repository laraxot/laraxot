<?php

declare(strict_types=1);

namespace Modules\Comment\Actions\Resolve;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Modules\Comment\Models\Contracts\CanComment;
use Spatie\QueueableAction\QueueableAction;

/**
 * Da riferimenti polimorfici (id + tipo) ai modelli commentator che implementano CanComment.
 * Unica risoluzione tipo -> modelli, condivisa da Comment::participatingCommentators() e da HasComments.
 */
class ResolveCommentatorModelsAction
{
    use QueueableAction;

    /**
     * @param  Collection<int, array{commentator_id: int|string|null, commentator_type: string|null}>  $references
     * @return Collection<int, CanComment>
     */
    public function execute(Collection $references): Collection
    {
        return $references
            ->filter(fn (array $reference): bool => $reference['commentator_type'] !== null)
            ->groupBy('commentator_type')
            ->flatMap(fn (Collection $related, int|string $type): Collection => $this->resolveByType($related, $type));
    }

    /**
     * @param  Collection<int, array{commentator_id: int|string|null, commentator_type: string}>  $related  commentator di un solo tipo
     * @return Collection<int, CanComment>
     */
    private function resolveByType(Collection $related, int|string $type): Collection
    {
        // PHP converte in int le chiavi numeriche: un alias morph "1" arriva qui come 1.
        $class = (string) $type;
        $resolvedClass = str_contains($class, '\\') ? $class : Relation::getMorphedModel($class);
        if (! is_string($resolvedClass) || ! is_subclass_of($resolvedClass, Model::class)) {
            return new Collection;
        }

        return $resolvedClass::query()
            ->whereIn((new $resolvedClass)->getKeyName(), $related->pluck('commentator_id')->toArray())
            ->get()
            ->toBase()
            ->whereInstanceOf(CanComment::class);
    }
}
