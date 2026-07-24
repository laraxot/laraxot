<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Support;

use Modules\TimberBilling\Models\TimberAgent;
use Modules\TimberBilling\Models\TimberAgentCommission;
use Modules\TimberBilling\Models\TimberProduct;

/**
 * Risolve la percentuale di provvigione di un agente su un prodotto, in ordine:
 * 1) regola sul prodotto, 2) regola sulla categoria, 3) regola di default,
 * 4) provvigione base dell'agente.
 */
final class TimberCommissionResolver
{
    public function rateFor(TimberAgent $agent, TimberProduct $product): float
    {
        $byProduct = TimberAgentCommission::query()
            ->where('agent_id', $agent->getKey())
            ->where('scope_type', 'product')
            ->where('product_id', $product->getKey())
            ->first();
        if ($byProduct !== null) {
            return (float) $byProduct->rate_percent;
        }

        $category = trim((string) ($product->category ?? ''));
        if ($category !== '') {
            $byCategory = TimberAgentCommission::query()
                ->where('agent_id', $agent->getKey())
                ->where('scope_type', 'category')
                ->where('category', $category)
                ->first();
            if ($byCategory !== null) {
                return (float) $byCategory->rate_percent;
            }
        }

        $default = TimberAgentCommission::query()
            ->where('agent_id', $agent->getKey())
            ->where('scope_type', 'default')
            ->first();
        if ($default !== null) {
            return (float) $default->rate_percent;
        }

        return $agent->commission_percent !== null ? (float) $agent->commission_percent : 0.0;
    }
}
