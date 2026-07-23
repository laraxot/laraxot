<?php

use function Livewire\Volt\{state, computed, mount};
use Illuminate\Support\Facades\Schema;

state([
    'eyebrow' => 'Prediction Market',
    'title' => 'Prevedi il Futuro',
    'subtitle' => 'La piattaforma di prediction market dove le tue previsioni contano',
    'primaryCta' => ['text' => 'INIZIA ORA', 'url' => '/register'],
    'secondaryCta' => ['text' => 'Esplora i Mercati', 'url' => '/predicts'],
    'highlights' => ['Multi-opzione', 'Risultati reali', 'Community attiva'],
    'stats' => [],
    'spotlightCards' => [],
]);

mount(function () {
    $this->stats = $this->getHeroStats();
    $this->spotlightCards = $this->getSpotlightCards();
});

$getHeroStats = function() {
    $action = new \Modules\Predict\Actions\GetHomepageHeroDataAction();
    $data = $action->execute();

    // Formatta i dati per la visualizzazione
    return [
        [
            'value' => $data['markets_available'] > 0 ? number_format($data['markets_available'], 0, ',', '.') : '0',
            'label' => 'Mercati Attivi',
            'icon' => 'heroicon-o-chart-bar',
        ],
        [
            'value' => $data['active_users'] > 0 ? number_format($data['active_users'], 0, ',', '.') : '0',
            'label' => 'Utenti Attivi',
            'icon' => 'heroicon-o-users',
        ],
        [
            'value' => $data['total_volume'] > 0 ? number_format($data['total_volume'] / 1000, 1, ',', '.') . 'K' : '0',
            'label' => 'Volume Totale',
            'icon' => 'heroicon-o-currency-dollar',
        ],
    ];
};

$getSpotlightCards = function() {
    $predictClass = 'Modules\\Predict\\Models\\Predict';
    
    if (!class_exists($predictClass)) {
        return [];
    }

    try {
        $predictModel = new $predictClass();
        $tableName = $predictModel->getTable();
        $connectionName = $predictModel->getConnectionName();

        $query = $predictClass::query()
            ->with(['ratings', 'category']);

        if (Schema::connection($connectionName)->hasColumn($tableName, 'is_active')) {
            $query->where('is_active', true);
        }

        return $query
            ->limit(2)
            ->get()
            ->map(function ($predict) {
                $totalParticipants = $predict->transactions()->distinct('user_id')->count('user_id') ?: 0;
                $totalVolume = $predict->transactions()->sum('amount') ?: 0;
                
                $outcomes = collect($predict->getRatingsPercentageByVolume())->map(function (array $outcome) {
                    return [
                        'id' => $outcome['id'] ?? null,
                        'title' => $outcome['title'] ?? 'Outcome',
                        'percentage' => (float) ($outcome['percentage'] ?? 0),
                        'image_url' => null,
                    ];
                })->sortByDesc('percentage')->values();

                $leadOutcome = $outcomes->first();

                return [
                    'url' => url(app()->getLocale() . '/predicts/' . $predict->slug),
                    'title' => $predict->title,
                    'category' => $predict->category ?? 'Generale',
                    'image_url' => $predict->image_url ?? null,
                    'lead_outcome' => $leadOutcome ? [
                        'title' => $leadOutcome['title'],
                        'percentage' => $leadOutcome['percentage'],
                        'image_url' => $leadOutcome['image_url'] ?? null,
                    ] : null,
                    'outcomes' => $outcomes->toArray(),
                    'participants' => $totalParticipants,
                    'volume' => $totalVolume,
                ];
            })
            ->toArray();
    } catch (\Throwable $e) {
        return [];
    }
};

?>

<div>
    <!-- Hero content will be rendered by cinematic.blade.php -->
    <!-- This Volt component provides real-time data -->
</div>
