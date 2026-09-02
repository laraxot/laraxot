<?php

declare(strict_types=1);

namespace Modules\Comment\Models\Collections;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Models\Contracts\CanComment;
use Modules\Comment\Models\Reaction;

/**
 * @extends Collection<int, Reaction>
 */
class ReactionCollection extends Collection
{
    /** @return SupportCollection<int, array{reaction: string, count: int, commentator_reacted: bool}> */
    public function summary(?CanComment $commentator = null): SupportCollection
    {
        $commentator ??= auth()->user();

        /** @var SupportCollection<string, Collection<int, Reaction>> $grouped */
        $grouped = SupportCollection::make($this->all())->groupBy('reaction');

        /** @var SupportCollection<int, array{reaction: string, count: int, commentator_reacted: bool}> */
        return $grouped
            ->map(function (SupportCollection $reactionCollection, string $reaction): array {
                return [
                    'reaction' => $reaction,
                    'count' => $reactionCollection->count(),
                ];
            })
            ->sortBy(function (array $summary): int {
                $allowed = array_values(array_filter(CommentConfigData::make()->allowedReactions, is_string(...)));
                $index = array_search($summary['reaction'], $allowed, true);

                return $index === false ? PHP_INT_MAX : $index;
            })
            ->map(function (array $summary) use ($commentator): array {
                $currentCommentatorReacted = $this->contains(
                    function (Reaction $reactionInCollection) use ($commentator, $summary): bool {
                        if ($summary['reaction'] !== $reactionInCollection->reaction) {
                            return false;
                        }

                        if (! $commentator instanceof CanComment) {
                            return false;
                        }

                        return $commentator->getMorphClass() === $reactionInCollection->commentator_type
                            && $commentator->getKey() === $reactionInCollection->commentator_id;
                    },
                );

                return array_merge($summary, [
                    'commentator_reacted' => $currentCommentatorReacted,
                ]);
            })
            ->values();
    }
}
