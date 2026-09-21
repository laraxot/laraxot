<?php

declare(strict_types=1);

namespace Themes\TwentyOne\Actions\Hero;

final class ResolveCinematicHeroDataAction
{
    /**
     * @param  array{text?:string,url?:string}|null  $ctaPrimary
     * @param  array{text?:string,url?:string}|null  $ctaSecondary
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

        // NB: il tema e' agnostico rispetto ai moduli di dominio (vedi
        // docs/NO_PREDICT_SPECIFIC_PAGES_IN_THEME.md): nessuna lettura diretta
        // da un modulo specifico qui, solo dati locali e fallback vuoti che i
        // blocchi CMS/widget di modulo possono sovrascrivere passando le CTA.
        return [
            'eyebrow' => $this->translateText('predict::home.hero.eyebrow.label', 'Mercati multi-opzione in primo piano'),
            'title' => $heroTitle ? $heroTitle : $this->translateText('predict::home.hero.title', 'Prevedi il Futuro'),
            'subtitle' => $heroSubtitle ? $heroSubtitle : $this->translateText('predict::home.hero.subtitle', 'Mercati visuali, percentuali calcolate dal database e flussi pensati per capire chi sta davvero salendo.'),
            'primaryCta' => [
                'text' => $ctaPrimary['text'] ?? $this->translateText('predict::home.hero.cta_primary.label', 'Esplora i mercati'),
                'url' => $ctaPrimary['url'] ?? $this->localizedUrl($locale, '/predicts'),
            ],
            'secondaryCta' => [
                'text' => $ctaSecondary['text'] ?? $this->translateText('predict::home.hero.cta_secondary.label', 'Crea un account'),
                'url' => $ctaSecondary['url'] ?? $this->localizedUrl($locale, '/register'),
            ],
            'stats' => $this->buildStats(),
            'highlights' => [
                $this->translateText('predict::home.hero.highlights.visual_first', 'Foto reali delle opzioni, non solo etichette'),
                $this->translateText('predict::home.hero.highlights.multi_outcome', 'Solo mercati con almeno 4 possibili risposte'),
                $this->translateText('predict::home.hero.highlights.db_backed', 'Percentuali e volumi derivati dai dati salvati nel DB'),
            ],
            'spotlightCards' => [],
        ];
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
     * @return array<int, array{value:string,label:string,icon:string}>
     */
    private function buildStats(): array
    {
        return [
            [
                'value' => $this->formatCompact(0),
                'label' => $this->translateText('predict::home.hero.stats.active_markets', 'Mercati attivi'),
                'icon' => 'heroicon-o-presentation-chart-bar',
            ],
            [
                'value' => $this->formatCompact(0),
                'label' => $this->translateText('predict::home.hero.stats.users', 'Utenti registrati'),
                'icon' => 'heroicon-o-user-group',
            ],
            [
                'value' => $this->formatCompact(0),
                'label' => $this->translateText('predict::home.hero.stats.volume', 'Crediti scambiati'),
                'icon' => 'heroicon-o-banknotes',
            ],
        ];
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
