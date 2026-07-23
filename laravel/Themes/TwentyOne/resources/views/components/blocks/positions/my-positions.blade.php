@props(['article' => $record])
<div class="flex flex-col w-full border border-gray-200 mb-3">
    <h2 class="text-2xl font-bold pt-4 pl-4">My positions</h2>
    <div class="flex flex-col gap-2 p-4">
        @livewire(\Modules\Predict\Filament\Widgets\MyPositionsWidget::class, [
            'predict' => $article,
        ])
    </div>
</div>
