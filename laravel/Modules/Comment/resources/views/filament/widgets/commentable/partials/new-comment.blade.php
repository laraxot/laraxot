{{--
    Nuovo commento (CMT-022). Il campo e' il form Filament dello schema `CommentTextForm` (MarkdownEditor), non una textarea:
    etichetta, aiuto ed errori li disegna Filament e restano accessibili da tastiera e screen reader.
--}}
<form wire:submit="comment" class="cmt-form" novalidate>
    {{ $this->form }}
    <h3 class="cmt-sr-only">{{ __('comment::txt.write_comment') }}</h3>

    @if(\Modules\Comment\Datas\CommentConfigData::make()->mentions['enabled'] ?? false)
        @livewire(\Modules\Comment\Filament\Widgets\Mention\MentionSearchWidget::class)
    @endif

    <div class="cmt-form-actions">
        <button type="submit" class="cmt-btn cmt-btn--primary" wire:target="comment" wire:loading.attr="disabled">
            {{ __('comment::txt.post_comment') }}
        </button>
    </div>
</form>
