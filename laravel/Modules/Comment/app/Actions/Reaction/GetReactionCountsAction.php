<?php

declare(strict_types=1);

namespace Modules\Comment\Actions\Reaction;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Reaction;
use Modules\Xot\Actions\Cast\SafeIntCastAction;
use Spatie\QueueableAction\QueueableAction;

class GetReactionCountsAction
{
    use QueueableAction;

    /** @return list<array{reaction: string, count: int}> */
    public function execute(Comment $comment): array
    {
        $allowedReactions = array_values(array_filter(CommentConfigData::make()->allowedReactions, is_string(...)));

        /** @var list<array{reaction: string, count: int}> */
        return $this->unsortedReactionCounts($comment)
            ->get()
            ->sortBy(function (Reaction $item) use ($allowedReactions): int {
                $index = array_search($item->reaction, $allowedReactions, true);

                return $index === false ? PHP_INT_MAX : $index;
            })
            ->values()
            ->map(static fn (Reaction $item): array => [
                'reaction' => $item->reaction,
                'count' => SafeIntCastAction::cast($item->count ?? 0),
            ])
            ->all();
    }

    /** @return HasMany<Reaction, Comment> */
    private function unsortedReactionCounts(Comment $comment): HasMany
    {
        return $comment->reactions()
            ->select('reaction', DB::raw('count(*) as count'))
            ->groupBy('reaction');
    }
}
