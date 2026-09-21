<?php

declare(strict_types=1);

namespace Themes\TwentyOne\Actions\Markets;

use Illuminate\Support\Collection;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

final class ResolveFeaturedMarketsGridDataAction
{
    /**
     * @return array{
     *     title:string,
     *     subtitle:string,
     *     showAllLink:string,
     *     showAllLabel:string,
     *     openMarketLabel:string,
     *     activeMarketsLabel:string,
     *     multiOutcomeLabel:string,
     *     visualOptionsLabel:string,
     *     freshnessLabel:string,
     *     educationLabel:string,
     *     emptyStateTitle:string,
     *     emptyStateBody:string,
     *     cards:Collection<int, array{
     *         title:string,
     *         slug:string,
     *         url:string,
     *         image_url:string|null,
     *         category:string|null,
     *         participants:int,
     *         volume:float,
     *         ends_at_human:string|null,
     *         outcomes:array<int, array{title:string, percentage:float, image_url:string|null}>
     *     }>
     * }
     */
    public function execute(?string $title = null, ?string $subtitle = null, ?string $showAllLink = null, int $limit = 12): array
    {
        $locale = app()->getLocale();

        // NB: il tema e' agnostico rispetto ai moduli di dominio (vedi
        // docs/NO_PREDICT_SPECIFIC_PAGES_IN_THEME.md): nessuna lettura diretta
        // da un modulo specifico qui, solo etichette locali e una griglia
        // vuota che i blocchi CMS/widget di modulo popolano passando $limit.
        return [
            'title' => $title ? $title : $this->tx('predict::home.featured_markets.title', 'Mercati in primo piano'),
            'subtitle' => $subtitle ? $subtitle : $this->tx('predict::home.featured_markets.subtitle', 'Scopri i mercati piu\' seguiti in questo momento'),
            'showAllLink' => $showAllLink ? $showAllLink : $this->localizedUrl($locale, '/predicts'),
            'showAllLabel' => $this->tx('predict::home.featured_markets.cta_all', 'Vedi tutti i mercati'),
            'openMarketLabel' => $this->tx('predict::actions.trade_market', 'Scambia ora'),
            'activeMarketsLabel' => $this->tx('predict::home.featured_markets.active_markets', 'Mercati attivi'),
            'multiOutcomeLabel' => $this->tx('predict::home.featured_markets.multi_outcome_focus', 'Piu\' opzioni per mercato'),
            'visualOptionsLabel' => $this->tx('predict::home.featured_markets.visual_options', 'Opzioni illustrate con immagini'),
            'freshnessLabel' => $this->tx('predict::home.featured_markets.freshness', 'Aggiornati in tempo reale'),
            'educationLabel' => $this->tx('predict::home.featured_markets.education', 'Pensati per capire chi sta davvero salendo'),
            'emptyStateTitle' => $this->tx('predict::predict_table.empty_state.no_markets_available.message', 'Nessun mercato disponibile'),
            'emptyStateBody' => $this->tx('predict::home.featured_markets.empty_body', 'Torna piu\' tardi per scoprire i prossimi mercati in evidenza'),
            'cards' => $this->buildCards($limit),
        ];
    }

    private function tx(string $key, string $fallback): string
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

    private function localizedUrl(string $locale, string $path): string
    {
        $resolved = LaravelLocalization::getLocalizedURL($locale, $path);

        return is_string($resolved) && $resolved !== ''
            ? $resolved
            : url('/'.$locale.$path);
    }

    /**
     * @return Collection<int, array{
     *     title:string,
     *     slug:string,
     *     url:string,
     *     image_url:string|null,
     *     category:string|null,
     *     participants:int,
     *     volume:float,
     *     ends_at_human:string|null,
     *     outcomes:array<int, array{title:string, percentage:float, image_url:string|null}>
     * }>
     */
    private function buildCards(int $limit): Collection
    {
        // Nessuna sorgente dati agganciata: il tema resta agnostico, un
        // blocco/widget di modulo puo' rimpiazzare questa azione passando
        // dati reali senza toccare il tema (vedi $limit riservato a quello).
        unset($limit);

        return Collection::make();
    }
}
