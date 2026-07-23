<?php

declare(strict_types=1);

namespace Themes\TwentyOne\Actions\Markets;

use Illuminate\Support\Collection;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Predict\Actions\Homepage\GetHomepageMarketCardsAction;

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

        return [
            'title' => $title ? $title : $this__('predict::home.featured_markets.title'),
            'subtitle' => $subtitle ? $subtitle : $this__('predict::home.featured_markets.subtitle'),
            'showAllLink' => $showAllLink ? $showAllLink : (LaravelLocalization::getLocalizedURL($locale, '/predicts') ?? url('/'.$locale.'/predicts')),
            'showAllLabel' => $this__('predict::home.featured_markets.cta_all'),
            'openMarketLabel' => $this__('predict::actions.trade_market'),
            'activeMarketsLabel' => $this__('predict::home.featured_markets.active_markets'),
            'multiOutcomeLabel' => $this__('predict::home.featured_markets.multi_outcome_focus'),
            'visualOptionsLabel' => $this__('predict::home.featured_markets.visual_options'),
            'freshnessLabel' => $this__('predict::home.featured_markets.freshness'),
            'educationLabel' => $this__('predict::home.featured_markets.education'),
            'emptyStateTitle' => $this__('predict::predict_table.empty_state.no_markets_available.message'),
            'emptyStateBody' => $this__('predict::home.featured_markets.empty_body'),
            'cards' => app(GetHomepageMarketCardsAction::class)->execute($limit, 'featured'),
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
}
