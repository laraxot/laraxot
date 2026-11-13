<?php

declare(strict_types=1);

namespace Modules\Comment\Filament\Widgets\Commentable;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\WithPagination;
use Modules\Comment\Actions\Comment\EnsureCommentRateLimitAction;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Datas\CommentsWidgetUiData;
use Modules\Comment\Enums\NotificationSubscriptionType;
use Modules\Comment\Filament\Widgets\Schemas\CommentTextForm;
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

    // Callers: Themes/Sixteen blog/detail + events/detail + Fixcity ticket-comments
    // (@livewire CommentsWidget). Unique FO comments UI; schema Comment morph.
    // User: «al posto di aggiungere i commenti non si vede piu' il contenuto».
    protected string $view = 'comment::filament.widgets.commentable.comments';

    public ?Model $model = null;

    /**
     * Persistito per Livewire annidato (no Eloquent dehydrate). Bloccato: le proprieta' pubbliche le puo' scrivere il browser, e
     * senza `#[Locked]` un utente potrebbe puntare il widget a un'altra entita' commentabile (un altro articolo, una segnalazione).
     *
     * @var class-string<Model>|null
     */
    #[Locked]
    public ?string $commentableType = null;

    #[Locked]
    public ?string $commentableKey = null;

    /** Esito dell'ultima azione, annunciato agli screen reader (`role="status"`). */
    public ?string $statusMessage = null;

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
     * @return class-string<CommentTextForm>
     */
    protected static function formClass(): string
    {
        return CommentTextForm::class;
    }

    protected static function schemaMethod(): string
    {
        return 'getCommentFormSchema';
    }

    public function updatedDataText(string $value): void
    {
        $this->dispatchMentionSearch($value);
    }

    public function updatedData(?string $value, string $key): void
    {
        if ($key !== 'text' || $value === null) {
            return;
        }

        $this->dispatchMentionSearch($value);
    }

    private function dispatchMentionSearch(string $value): void
    {
        if (! CommentConfigData::make()->mentionsEnabled()) {
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
        $current = is_string($this->data['text'] ?? null) ? $this->data['text'] : '';
        $replaced = preg_replace('/@[^\s{]*$/', $token.' ', $current);
        $this->data['text'] = is_string($replaced) ? $replaced : $current.' '.$token;
    }

    public function comment(): void
    {
        $commentable = $this->resolveCommentable();

        // Un ospite o chi non puo' commentare riceve 403: senza questo controllo l'anonimo arrivava a `HasComments::comment()` e
        // finiva in un'eccezione (500). La policy e' la fonte di verita': le proprieta' pubbliche del widget le scrive il browser.
        Gate::authorize('create', Comment::class);
        app(EnsureCommentRateLimitAction::class)->execute('data.text');

        $state = $this->form->getState();
        $text = trim(is_string($state['text'] ?? null) ? $state['text'] : '');

        $comment = $commentable->comment($text);

        $this->form->fill([]);
        $this->statusMessage = $comment->isApproved() ? __('comment::txt.comment_posted') : __('comment::txt.pending_approval');

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

        /** @var CanComment|null $currentUser */
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
            ->approved()
            // Solo il primo livello: le risposte si leggono da `nestedComments`. I dati di esempio salvano le risposte con lo
            // stesso commentable del padre e `parent_id` valorizzato, e senza questo filtro comparivano anche in lista.
            ->whereNull('parent_id')
            ->with([
                'commentator',
                'reactions.commentator',
                'nestedComments' => static function (mixed $nested): void {
                    // Eager-load constraint riceve Relation (HasMany), non Builder tipizzato.
                    $nested->whereNotNull('approved_at')->with(['commentator', 'reactions.commentator']);
                },
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
        $commentable = CommentsWidgetCommentableResolver::resolve($this);
        if (Gate::getPolicyFor($commentable) !== null) {
            // Reading an approved public thread is not an update operation. Write actions
            // still authorize `create`/`update` at their own trust boundary.
            Gate::authorize('view', $commentable);
        }

        return $commentable;
    }
}
