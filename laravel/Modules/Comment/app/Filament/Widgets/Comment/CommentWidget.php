<?php

declare(strict_types=1);

namespace Modules\Comment\Filament\Widgets\Comment;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Locked;
use Modules\Comment\Actions\Comment\CheckReplyAllowedAction;
use Modules\Comment\Actions\Comment\EnsureCommentRateLimitAction;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Datas\CommentWidgetUiData;
use Modules\Comment\Filament\Widgets\Schemas\CommentTextForm;
use Modules\Comment\Models\Comment;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;

/** Front-office actions for one comment: reply, edit, moderation and reactions. */
class CommentWidget extends XotBaseSchemaWidget
{
    use AuthorizesRequests;

    protected static bool $isDiscovered = false;

    #[Locked]
    public ?Comment $comment = null;

    #[Locked]
    public CommentWidgetUiData $uiConfig;

    public ?string $statusMessage = null;

    public function __construct()
    {
        $this->uiConfig = CommentWidgetUiData::from([]);
        parent::__construct();
    }

    public function mount(
        ?Comment $comment = null,
        bool $showAvatar = true,
        bool $newestFirst = false,
        bool $writable = true,
        bool $showReplies = false,
        bool $showReactions = false,
    ): void {
        $this->form->fill([]);
        $this->comment = $comment;
        $this->uiConfig = CommentWidgetUiData::from([
            'showAvatar' => $showAvatar,
            'newestFirst' => $newestFirst,
            'writable' => $writable,
            'showReplies' => $showReplies,
            'showReactions' => $showReactions,
            'depth' => $comment?->depth() ?? 0,
        ]);
    }

    protected static function formClass(): string
    {
        return CommentTextForm::class;
    }

    protected static function schemaMethod(): string
    {
        return 'getCommentFormSchema';
    }

    public function canReply(): bool
    {
        return $this->comment instanceof Comment && app(CheckReplyAllowedAction::class)->execute($this->comment);
    }

    public function startReplying(): void
    {
        $comment = $this->requireComment();
        $this->authorizeReply($comment);
        $this->uiConfig->isEditing = false;
        $this->uiConfig->isReplying = true;
        $this->statusMessage = null;
        $this->form->fill([]);
    }

    public function stopReplying(): void
    {
        $this->uiConfig->isReplying = false;
        $this->resetErrorBag();
        $this->form->fill([]);
    }

    public function reply(): void
    {
        $comment = $this->requireComment();
        $this->authorizeReply($comment);
        app(EnsureCommentRateLimitAction::class)->execute('data.text');
        $reply = $comment->comment($this->submittedText());
        $comment->load('nestedComments.commentator');
        $this->uiConfig->isReplying = false;
        $this->form->fill([]);
        $this->statusMessage = $reply->isApproved() ? __('comment::txt.reply_posted') : __('comment::txt.pending_approval');
        $this->dispatch('reply-created');
    }

    public function startEditing(): void
    {
        $comment = $this->requireComment();
        $this->authorize('update', $comment);
        $this->uiConfig->isReplying = false;
        $this->uiConfig->isEditing = true;
        $this->statusMessage = null;
        $this->form->fill(['text' => $comment->original_text]);
    }

    public function stopEditing(): void
    {
        $this->uiConfig->isEditing = false;
        $this->resetErrorBag();
        $this->form->fill([]);
    }

    public function edit(): void
    {
        $comment = $this->requireComment();
        $this->authorize('update', $comment);
        app(EnsureCommentRateLimitAction::class)->execute('data.text');
        $comment->update(['original_text' => $this->submittedText()]);
        $this->uiConfig->isEditing = false;
        $this->form->fill([]);
        $this->statusMessage = __('comment::txt.comment_saved');
    }

    public function delete(): void
    {
        $comment = $this->requireComment();
        $this->authorize('delete', $comment);
        $comment->delete();
        $this->comment = null;
        $this->dispatch('delete');
    }

    public function approve(): void
    {
        $comment = $this->requireComment();
        $this->authorize('approve', $comment);
        $comment->approve();
    }

    public function reject(): void
    {
        $comment = $this->requireComment();
        $this->authorize('reject', $comment);
        $comment->reject();
        $this->comment = null;
        $this->dispatch('delete');
    }

    public function toggleReaction(string $reaction): void
    {
        $allowed = array_values(array_filter(CommentConfigData::make()->allowedReactions, is_string(...)));
        if (! $this->comment instanceof Comment || ! in_array($reaction, $allowed, true)) {
            return;
        }

        $reactionModel = $this->comment->findReaction($reaction);
        if ($reactionModel) {
            $reactionModel->delete();
            $this->comment->load('reactions');

            return;
        }

        $this->comment->react($reaction);
        $this->comment->load('reactions');
    }

    private function requireComment(): Comment
    {
        abort_unless($this->comment instanceof Comment, 404);

        return $this->comment;
    }

    private function authorizeReply(Comment $comment): void
    {
        $this->authorize('create', Comment::class);
        abort_unless(app(CheckReplyAllowedAction::class)->execute($comment), 403);
    }

    private function submittedText(): string
    {
        $state = $this->form->getState();

        return trim(is_string($state['text'] ?? null) ? $state['text'] : '');
    }
}
