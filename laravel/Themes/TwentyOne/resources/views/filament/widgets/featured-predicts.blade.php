{{-- 
    Featured Predicts Widget
    
    PHILOSOPHY:
    - Livewire mount returns HTML string (NO ->html() call)
    - Featured predicts for homepage
    - Multi-outcome markets only (minimum 4 outcomes)
--}}

<div class="filament-table-widget w-full antigravity-field" data-antigravity-field>
    {{-- Background gradient effect --}}
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(56,189,248,0.08),transparent_28%),radial-gradient(circle_at_bottom_right,rgba(14,165,233,0.06),transparent_32%)]"></div>
    
    {{-- Grid pattern --}}
    <div class="pointer-events-none absolute inset-0" style="background-image: linear-gradient(rgba(148, 163, 184, 0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(148, 163, 184, 0.03) 1px, transparent 1px); background-size: 42px 42px; mask-image: radial-gradient(circle at center, black 40%, transparent 95%);"></div>
    
    {{-- Content container --}}
    <div class="relative">
        @livewire(
            \Modules\Predict\Filament\Widgets\FeaturedPredictsWidget::class,
            [
                'homepageMode' => true,
                'minimumOutcomes' => 4, {{-- Show only multi-outcome (4+ outcomes) --}}
                'showTableControls' => false,
            ],
            key('featured-predicts-table-widget')
        )
    </div>
</div>
