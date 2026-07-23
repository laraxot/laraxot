{{--
    Predict Table Widget - Front-Office Bridge

    PHILOSOPHY:
    - Widget container = trasparente (si fonde con lo sfondo)
    - Predict cards = bg proprio (slate-900/50 con backdrop-blur)
    - Kinetic effects = hover, entrance animations
    - NO Livewire::mount()->html() (causa errori)
    - USARE @livewire() directive
    - DARK THEME = testo bianco su sfondo scuro

    DOCS:
    - docs/project/FILAMENT_WIDGET_TRANSPARENT_BACKGROUND.md
    - docs/project/SINGLE_DESIGN_SYSTEM_PHILOSOPHY.md
    - docs/project/KINETIC_DESIGN_IMPLEMENTATION.md
--}}

<div class="filament-table-widget w-full antigravity-field" data-antigravity-field>
    {{-- Background gradient effect --}}
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(56,189,248,0.08),transparent_28%),radial-gradient(circle_at_bottom_right,rgba(14,165,233,0.06),transparent_32%)]"></div>

    {{-- Grid pattern --}}
    <div class="pointer-events-none absolute inset-0" style="background-image: linear-gradient(rgba(148, 163, 184, 0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(148, 163, 184, 0.03) 1px, transparent 1px); background-size: 42px 42px; mask-image: radial-gradient(circle at center, black 40%, transparent 95%);"></div>

    {{-- Content container with dark zinc theme --}}
    <div class="relative bg-zinc-950/80 backdrop-blur-md rounded-xl border border-zinc-800/50 p-3 sm:p-4">
        @livewire(
            \Modules\Predict\Filament\Widgets\PredictTableWidget::class,
            [
                'homepageMode' => false,
                'minimumOutcomes' => 3,
                'showTableControls' => true,
            ],
            key('predict-table-widget-bridge')
        )
    </div>
</div>
