{{--
    Comment widget pure Filament (no Livewire custom)
    Renders the Commentable widget for the given model.
--}}
{{ \Filament\View::render('filament.widgets.commentable', ['model' => $model]) }}