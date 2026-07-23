<?php

namespace Spatie\Comments\Actions;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\Comments\Models\Concerns\Interfaces\CanComment;
use Spatie\Comments\Support\Config;

class ResolveMentionsAutocompleteAction
{
    /** @return CanComment[] */
    public function execute(string $query, $commentable): array
    {
        $commentatorModel = Config::commentatorModelClass();

        if (! $commentatorModel) {
            throw new Exception('A commentator model needs to be set in config/comments.php when enabling mentions.');
        }

        /** @var Collection $inThreadMatches */
        $inThreadMatches = $commentatorModel::where(Config::commentatorModelNameField(), 'like', "%$query%")
            ->whereHas('commentatorComments', function ($query) use ($commentable): void {
                $query->where('commentable_id', $commentable->id);
            })
            ->whereNot('id', auth()->id())
            ->limit(10)
            ->get();

        /** @var Collection $otherMatches */
        $otherMatches = $commentatorModel::where(Config::commentatorModelNameField(), 'like', "%$query%")
            ->whereNot('id', auth()->id())
            ->whereNotIn('id', $inThreadMatches->map(fn (Model $model) => $model->getKey()))
            ->limit(10 - count($inThreadMatches))
            ->get();

        return $inThreadMatches->merge($otherMatches)->values()->all();
    }
}
