<?php

declare(strict_types=1);

namespace Modules\Comment\Http\Livewire;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Comment\Enums\NotificationSubscriptionType;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;
use Modules\Comment\Models\Contracts\Commentable;
use Modules\Comment\Datas\CommentConfigData;

class CommentsComponent extends Component
{
    use WithPagination;

    public ?Model $model = null;

    public string $text = '';

    public bool $writable = true;

    public bool $showAvatars = true;

    public bool $notifyOptions = false;

    public bool $newestFirst = false;

    public bool $showReplies = false;

    public bool $showReactions = false;

    public string $notifySubType = '';

    public bool $readOnly = false;

    public ?bool $hideAvatars = null;

    public bool $hideNotifyOptions = true;

    public bool $noReplies = true;

    public bool $noReactions = true;

    /** @var array<string, string> */
    protected $listeners = [
        'delete' => '$refresh',
        'reply-created' => 'saveNotificationSubscription',
    ];

    public function mount(?Model $model = null): void
    {
        $this->model = $model;
        $this->writable = ! $this->readOnly;
        $this->showReplies = ! $this->noReplies;
        $this->showReactions = ! $this->noReactions;
        $this->notifyOptions = ! $this->hideNotifyOptions;
        $this->showAvatars = $this->hideAvatars !== null ? ! $this->hideAvatars : CommentConfigData::make()->showAvatars();

        $user = auth()->user();
        if ($user instanceof CanComment && $this->model instanceof Commentable) {
            $subscriptionType = $user->notificationSubscriptionType($this->model);
            $this->notifySubType = $subscriptionType !== null
                ? $subscriptionType->value
                : NotificationSubscriptionType::Participating->value;
        }
    }

    public function comment(): void
    {
        $this->validate(['text' => 'required|string|min:1']);

        $this->resolveCommentable()->comment($this->text);

        $this->text = '';

        $pageName = CommentConfigData::make()->paginationPageName();
        if ($this->newestFirst) {
            $this->resetPage($pageName);
        }
        if (! $this->newestFirst) {
            $this->gotoPage($this->comments()->lastPage(), $pageName);
        }

        $this->saveNotificationSubscription();

        $this->dispatch('comment-added');
    }

    public function updateSelectedNotificationSubscriptionType(string $type): void
    {
        $this->notifySubType = $type;

        $this->saveNotificationSubscription();
    }

    public function saveNotificationSubscription(): void
    {
        if (! $this->notifyOptions || ! $this->model instanceof Commentable) {
            return;
        }

        $currentUser = auth()->user();
        if (! $currentUser instanceof CanComment) {
            return;
        }

        $type = NotificationSubscriptionType::from($this->notifySubType);

        if ($type === NotificationSubscriptionType::None) {
            $currentUser->unsubscribeFromCommentNotifications($this->model);

            return;
        }

        $currentUser->subscribeToCommentNotifications($this->model, $type);
    }

    /**
     * @return LengthAwarePaginator<int, Comment>
     */
    #[Computed]
    public function comments(): LengthAwarePaginator
    {
        $query = $this->resolveCommentable()
            ->comments()
            ->with([
                'commentator',
                'nestedComments.commentator',
                'reactions.commentator',
                'nestedComments.reactions.commentator',
            ])
            ->when(
                $this->newestFirst,
                fn (Builder $builder) => $builder->latest(),
                fn (Builder $builder) => $builder->oldest(),
            );

        $perPage = CommentConfigData::make()->paginationCount();

        /** @var LengthAwarePaginator<int, Comment> $paginator */
        $paginator = $query->paginate(
            $perPage,
            ['*'],
            CommentConfigData::make()->paginationPageName(),
        );

        return $paginator;
    }

    public function render(): View
    {
        /** @var view-string $view */
        $view = 'comment::livewire.comments';

        return view($view);
    }

    private function resolveCommentable(): Commentable
    {
        if (! $this->model instanceof Commentable) {
            throw new InvalidArgumentException('CommentsComponent requires a Commentable model.');
        }

        return $this->model;
    }
}
