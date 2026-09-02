<?php

declare(strict_types=1);

namespace Modules\Comment\Filament\Widgets\Commentable;

use Filament\Schemas\Components\Component;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Datas\CommentsWidgetUiData;
use Modules\Comment\Enums\NotificationSubscriptionType;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;
use Modules\Comment\Models\Contracts\Commentable;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;

use function Safe\preg_match;
use function Safe\preg_replace;

class CommentsWidget extends XotBaseSchemaWidget
{
    use WithPagination;

    protected static bool $isDiscovered = false;

    // protected string $view = 'comment::filament.widgets.commentable.comments';

    public ?Model $model = null;

    /** @var class-string<Model>|null Persistito per Livewire annidato (no Eloquent dehydrate). */
    public ?string $commentableType = null;

    public ?string $commentableKey = null;

    public CommentsWidgetUiData $uiConfig;

    public bool $readOnly = false;

    public ?bool $hideAvatars = null;

    public bool $hideNotifications = true;

    public bool $noReplies = true;

    public bool $noReactions = true;

    public bool $newestFirst = false;

    public ?string $noCommentsText = null;

    public function __construct()
    {
        $this->uiConfig = CommentsWidgetUiData::from([]);
        parent::__construct();
    }

    /** @var array<string, string> */
    protected $listeners = [
        'delete' => '$refresh',
        'reply-created' => 'saveNotificationSubscription',
        'mention-selected' => 'insertMention',
    ];

    /**
     * @param  array<string, mixed>|CommentsWidgetUiData|null  $uiConfig
     */
    public function mount(
        ?Model $model = null,
        ?string $commentableType = null,
        int|string|null $commentableKey = null,
        array|CommentsWidgetUiData|null $uiConfig = null,
    ): void {
        $this->form->fill([]);
        CommentsWidgetCommentableResolver::assignModel($this, $model, $commentableType, $commentableKey);
        $this->uiConfig = $this->buildUiConfig($uiConfig);
        $this->applyNotificationSubscriptionDefault();
    }

    /**
     * @param  array<string, mixed>|CommentsWidgetUiData|null  $uiConfig
     */
    private function buildUiConfig(array|CommentsWidgetUiData|null $uiConfig): CommentsWidgetUiData
    {
        if ($uiConfig instanceof CommentsWidgetUiData) {
            return $uiConfig;
        }

        $showAvatars = $this->hideAvatars !== null
            ? ! $this->hideAvatars
            : (bool) (CommentConfigData::make()->uiSettings['show_avatars'] ?? true);

        return CommentsWidgetUiData::from(array_merge([
            'writable' => ! $this->readOnly,
            'showAvatars' => $showAvatars,
            'notifyOptions' => ! $this->hideNotifications,
            'newestFirst' => $this->newestFirst,
            'showReplies' => ! $this->noReplies,
            'showReactions' => ! $this->noReactions,
            'noCommentsText' => $this->noCommentsText,
        ], is_array($uiConfig) ? $uiConfig : []));
    }

    private function applyNotificationSubscriptionDefault(): void
    {
        $user = auth()->user();
        if (! $user instanceof CanComment || ! $this->model instanceof Commentable) {
            return;
        }

        $subscriptionType = $user->notificationSubscriptionType($this->model);
        $this->uiConfig->notifySubType = $subscriptionType !== null
            ? $subscriptionType->value
            : NotificationSubscriptionType::Participating->value;
    }

    /**
     * @return array<int|string, Component>
     */
    public function getFormSchema(): array
    {
        return [];
    }

    public function updatedUiConfigText(string $value): void
    {
        if (! (CommentConfigData::make()->mentions['enabled'] ?? false)) {
            return;
        }

        if (preg_match('/@([^\s{]*)$/', $value, $matches) === 1 && isset($matches[1])) {
            $this->dispatch('mention-search', query: (string) $matches[1]);
        }
    }

    public function insertMention(int $userId, ?string $displayName = null): void
    {
        if (! (CommentConfigData::make()->mentions['enabled'] ?? false)) {
            return;
        }

        $modelClass = CommentConfigData::make()->models['commentator'] ?? null;
        if (! is_string($modelClass) || $modelClass === '' || ! is_a($modelClass, Model::class, true)) {
            return;
        }

        if ($displayName === null || $displayName === '') {
            $displayName = $this->resolveMentionDisplayName($userId);
            if ($displayName === null) {
                return;
            }
        }

        $token = '@{'.$userId.'|'.$displayName.'}';
        $replaced = preg_replace('/@[^\s{]*$/', $token.' ', $this->uiConfig->text);
        $this->uiConfig->text = $replaced !== null ? $replaced : $this->uiConfig->text.' '.$token;
    }

    public function comment(): void
    {
        $this->validate(['uiConfig.text' => ['required', 'string', 'min:1']]);

        $this->resolveCommentable()->comment($this->uiConfig->text);

        $this->uiConfig->text = '';

        $pageName = (string) (CommentConfigData::make()->pagination['page_name'] ?? 'page');
        if ($this->uiConfig->newestFirst) {
            $this->resetPage($pageName);
        }
        if (! $this->uiConfig->newestFirst) {
            $this->gotoPage($this->comments()->lastPage(), $pageName);
        }

        $this->saveNotificationSubscription();

        $this->dispatch('comment-added');
    }

    public function updateSelectedNotificationSubscriptionType(string $type): void
    {
        $this->uiConfig->notifySubType = $type;

        $this->saveNotificationSubscription();
    }

    public function saveNotificationSubscription(): void
    {
        if (! $this->uiConfig->notifyOptions || ! $this->model instanceof Commentable) {
            return;
        }

        /** @var \Modules\Comment\Models\Contracts\CanComment|null $currentUser */
         $currentUser = auth()->user();
        if (! $currentUser instanceof CanComment) {
            return;
        }

        $type = NotificationSubscriptionType::from($this->uiConfig->notifySubType);

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
                $this->uiConfig->newestFirst,
                fn (Builder $builder) => $builder->latest(),
                fn (Builder $builder) => $builder->oldest(),
            );

        $pagination = CommentConfigData::make()->pagination;
        $perPage = $pagination['results'] ?? 10_000;

        /** @var \Illuminate\Pagination\LengthAwarePaginator<int, Comment> $paginator */
        $paginator = $query->paginate(
            is_int($perPage) ? $perPage : 10_000,
            ['*'],
            (string) ($pagination['page_name'] ?? 'page'),
        );

        return $paginator;
    }

    public function paginationView(): string
    {
        $theme = (string) (CommentConfigData::make()->pagination['theme'] ?? 'tailwind');

        if (view()->exists($theme)) {
            return $theme;
        }

        if (view()->exists('livewire::'.$theme)) {
            return 'livewire::'.$theme;
        }

        return 'livewire::tailwind';
    }

    private function resolveMentionDisplayName(int $userId): ?string
    {
        $modelClass = CommentConfigData::make()->models['commentator'] ?? null;
        if (! is_string($modelClass) || $modelClass === '' || ! is_a($modelClass, Model::class, true)) {
            return null;
        }

        /** @var class-string<Model> $modelClass */
        $user = $modelClass::query()->find($userId);
        if (! $user instanceof Model) {
            return null;
        }

        $nameField = CommentConfigData::make()->models['name'] ?? 'name';

        return is_string($user->{$nameField} ?? null) ? $user->{$nameField} : (string) $userId;
    }

    private function resolveCommentable(): Commentable
    {
        return CommentsWidgetCommentableResolver::resolve($this);
    }
}
