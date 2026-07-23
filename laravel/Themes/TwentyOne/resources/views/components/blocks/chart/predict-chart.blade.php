@props(['article' => $record])
<div>
    @livewire(\Modules\Predict\Filament\Resources\PredictResource\Widgets\PredictChartWidget::class,[
        'predict' => $article,
    ])
</div>