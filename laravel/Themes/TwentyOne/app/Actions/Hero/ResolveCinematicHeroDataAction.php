<?php

declare(strict_types=1);

namespace Themes\TwentyOne\Actions\Hero;

use Illuminate\Support\Facades\Schema;
use Modules\Predict\Actions\Homepage\GetHomepageHeroDataAction;
use Modules\Predict\Actions\Homepage\GetHomepageMarketCardsAction;
use Modules\Predict\Models\Predict;
use Throwable;

final class ResolveCinematicHeroDataAction
{
    /**
     * @return array{
     *     eyebrow:string,
     *     title:string,
     *     subtitle:string,
     *     primaryCta:array{text:string,url:string},
     *     secondaryCta:array{text:string,url:string},
     *     stats:array<int, array{value:string,label:string,icon:string}>,
     *     highlights:array<int, string>,
     *     spotlightCards:array<int, array{
     *         title:string,
     *         slug:string,
     *         url:string,
     *         image_url:string|null,
     *         category:string|null,
     *         participants:int,
     *         volume:float,
     *         ends_at_human:string|null,
     *         outcomes:array<int, array{title:string,percentage:float,image_url:string|null}>,
     *         lead_outcome:array{title:string,percentage:float,image_url:string|null}|null
     *     }>
     * }
     */
    public function execute(
        ?string $heroTitle = null,
        ?string $heroSubtitle = null,
        ?array $ctaPrimary = null,
        ?array $ctaSecondary = null,
    ): array {
        $locale = app()->getLocale();
        $heroStats = app(GetHomepageHeroDataAction::class)->execute();

        $data = [
            'eyebrow' => $this->translateText('predict::home.hero.eyebrow.label', 'Mercati multi-opzione in primo piano'),
            'title' => $heroTitle ? $heroTitle : $this->translateText('predict::home.hero.title', 'Prevedi il Futuro'),
            'subtitle' => $heroSubtitle ? $heroSubtitle : $this->translateText('predict::home.hero.subtitle', 'Mercati visuali, percentuali calcolate dal database e flussi pensati per capire chi sta davvero salendo.'),
            'primaryCta' => [
                'text' => (string) ($ctaPrimary['text'] ?? $this->translateText('predict::home.hero.cta_primary.label', 'Esplora i mercati')),
                'url' => (string) ($ctaPrimary['url'] ?? $this->localizedUrl($locale, '/predicts')),
            ],
            'secondaryCta' => [
                'text' => (string) ($ctaSecondary['text'] ?? $this->translateText('predict::home.hero.cta_secondary.label', 'Crea un account')),
                'url' => (string) ($ctaSecondary['url'] ?? $this->localizedUrl($locale, '/register')),
            ],
            'stats' => $this->buildStats($heroStats),
            'highlights' => [
                $this->translateText('predict::home.hero.highlights.visual_first', 'Foto reali delle opzioni, non solo etichette'),
                $this->translateText('predict::home.hero.highlights.multi_outcome', 'Solo mercati con almeno 4 possibili risposte'),
                $this->translateText('predict::home.hero.highlights.db_backed', 'Percentuali e volumi derivati dai dati salvati nel DB'),
            ],
            'spotlightCards' => [],
        ];

        try {
            if ((int) $heroStats['marketsCount'] === 0) {
                $data['stats'][0]['value'] = (string) Predict::query()
                    ->visible()
                    ->has('ratings', '>=', 4)
                    ->count();
            }

            $data['spotlightCards'] = $this->buildSpotlightCards();
        } catch (Throwable $e) {
            report($e);
        }

        try {
            if ($data['spotlightCards'] === []) {
                $query = Predict::query()
                    ->visible()
                    ->where('show_on_homepage', true)
                    ->has('ratings', '>=', 4);

                if ($this->hasVolumeColumn()) {
                    $query->orderByDesc('sum_credit_yes');
                }

                $data['spotlightCards'] = $query
                    ->orderByDesc('updated_at')
                    ->limit(2)
                    ->get()
                    ->map(fn (Predict $predict): array => [
                        'title' => (string) $predict->slug,
                        'slug' => (string) $predict->slug,
                        'url' => $this->localizedUrl($locale, '/predicts/'.$predict->slug),
                        'image_url' => is_string($predict->main_image_url) ? $predict->main_image_url : null,
                        'category' => null,
                        'participants' => 0,
                        'volume' => 0.0,
                        'ends_at_human' => null,
                        'outcomes' => [],
                        'lead_outcome' => null,
                    ])
                    ->all();
            }
        } catch (Throwable $e) {
            report($e);
        }

        return $data;
    }

    private function translateText(string $key, string $fallback): string
    {
        $translated = __($key);
        if (is_string($translated) && $translated !== $key) {
            return $translated;
        }

        $translatedLabel = __($key.'.label');
        if (is_string($translatedLabel) && $translatedLabel !== $key.'.label') {
            return $translatedLabel;
        }

        return $fallback;
    }

    /**
     * @param  array{marketsCount:int,usersCount:int,volumeCredits:int}  $heroStats
     * @return array<int, array{value:string,label:string,icon:string}>
     */
    private function buildStats(array $heroStats): array
    {
        return [
            [
                'value' => $this->formatCompact((int) $heroStats['marketsCount']),
                'label' => $this->translateText('predict::home.hero.stats.active_markets', 'Mercati attivi'),
                'icon' => 'heroicon-o-presentation-chart-bar',
            ],
            [
                'value' => $this->formatCompact((int) $heroStats['usersCount']),
                'label' => $this->translateText('predict::home.hero.stats.users', 'Utenti registrati'),
                'icon' => 'heroicon-o-user-group',
            ],
            [
                'value' => $this->formatCompact((int) $heroStats['volumeCredits']),
                'label' => $this->translateText('predict::home.hero.stats.volume', 'Crediti scambiati'),
                'icon' => 'heroicon-o-banknotes',
            ],
        ];
    }

    /**
     * @return array<int, array{
     *     title:string,
     *     slug:string,
     *     url:string,
     *     image_url:string|null,
     *     category:string|null,
     *     participants:int,
     *     volume:float,
     *     ends_at_human:string|null,
     *     outcomes:array<int, array{title:string,percentage:float,image_url:string|null}>,
     *     lead_outcome:array{title:string,percentage:float,image_url:string|null}|null
     * }>
     */
    private function buildSpotlightCards(): array
    {
        return app(GetHomepageMarketCardsAction::class)->execute(3, 'featured')
            ->map(
                static function (array $card): array {
                    return [
                        ...$card,
                        'lead_outcome' => $card['outcomes'][0] ?? null,
                    ];
                }
            )
            ->values()
            ->all();
    }

    private function hasVolumeColumn(): bool
    {
        $predict = new Predict;

        return Schema::connection($predict->getConnectionName())
            ->hasColumn($predict->getTable(), 'sum_credit_yes');
    }

    private function localizedUrl(string $locale, string $path): string
    {
        $localization = app('laravellocalization');
        $resolved = $localization->getLocalizedURL($locale, $path);

        return is_string($resolved) && $resolved !== ''
            ? $resolved
            : url('/'.$locale.$path);
    }

    private function formatCompact(int $value): string
    {
        if ($value >= 1000000) {
            return number_format($value / 1000000, 1, ',', '.').'M';
        }

        if ($value >= 1000) {
            return number_format($value / 1000, 1, ',', '.').'K';
        }

        return number_format($value, 0, ',', '.');
    }
}
