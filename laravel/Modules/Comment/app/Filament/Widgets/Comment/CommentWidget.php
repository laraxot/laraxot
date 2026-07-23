<?php

declare(strict_types=1);

namespace Modules\Comment\Filament\Widgets\Comment;

use Filament\Schemas\Components\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Datas\CommentWidgetUiData;
use Modules\Comment\Models\Comment;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;

class CommentWidget extends XotBaseSchemaWidget
{
    use AuthorizesRequests;

    protected static bool $isDiscovered = false;

    // protected string $view = 'comment::filament.widgets.comment.comment';

    public ?Comment $comment = null;

    public CommentWidgetUiData $uiConfig;

    public function __construct()
    {
        $this->uiConfig = CommentWidgetUiData::from([]);
        parent::__construct();
    }

    /**
     * @param  array<string, mixed>|CommentWidgetUiData|null  $uiConfig
     */
    public function mount(?Comment $comment = null, array|CommentWidgetUiData|null $uiConfig = null): void
    {
        $this->form->fill([]);
        $this->comment = $comment;
        $this->uiConfig = $uiConfig instanceof CommentWidgetUiData
            ? $uiConfig
            : CommentWidgetUiData::from(is_array($uiConfig) ? $uiConfig : []);
    }

    /**
     * @return array<int|string, Component>
     */
    public function getFormSchema(): array
    {
        return [];
    }

    public function startEditing(): void
    {
        if (! $this->comment instanceof Comment) {
            return;
        }

        $this->uiConfig->isEditing = true;
        $this->uiConfig->editText = $this->comment->original_text;
    }

    public function stopEditing(): void
    {
        $this->uiConfig->isEditing = false;
    }

    public function edit(): void
    {
        if (! $this->comment instanceof Comment) {
            return;
        }

        $this->authorize('update', $this->comment);

        $this->validate(['uiConfig.editText' => ['required', 'string', 'min:1']]);

        $this->comment->update([
            'original_text' => $this->uiConfig->editText,
        ]);

        $this->uiConfig->isEditing = false;
    }

    public function reply(): void
    {
        if (! $this->comment instanceof Comment) {
            return;
        }

        $this->validate(['uiConfig.replyText' => ['required', 'string', 'min:1']]);

        $this->comment->comment($this->uiConfig->replyText);
        $this->comment->load('nestedComments.commentator');

        $this->uiConfig->replyText = '';
        $this->dispatch('reply-created');
    }

    public function delete(): void
    {
        if (! $this->comment instanceof Comment) {
            return;
        }

        $this->authorize('delete', $this->comment);

        $this->comment->delete();
        $this->comment = null;

        $this->dispatch('delete');
    }

    public function approve(): void
    {
        if (! $this->comment instanceof Comment) {
            return;
        }

        $this->authorize('approve', $this->comment);

        $this->comment->approve();
    }

    public function reject(): void
    {
        if (! $this->comment instanceof Comment) {
            return;
        }

        $this->authorize('reject', $this->comment);

        $this->comment->reject();
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
}
