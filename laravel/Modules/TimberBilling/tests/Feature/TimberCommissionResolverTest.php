<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Tests\Feature;

use Modules\TimberBilling\Tests\TestCase;

uses(TestCase::class);

use Modules\TimberBilling\Models\TimberAgent;
use Modules\TimberBilling\Models\TimberAgentCommission;
use Modules\TimberBilling\Models\TimberProduct;
use Modules\TimberBilling\Support\TimberCommissionResolver;

function commission(TimberAgent $agent, TimberProduct $product): float
{
    return app(TimberCommissionResolver::class)->rateFor($agent, $product);
}

it('prefers a product-specific commission over category, default and base', function (): void {
    $agent = TimberAgent::factory()->create(['commission_percent' => 4.0]);
    $product = TimberProduct::factory()->create(['category' => 'Sboli legnami']);

    TimberAgentCommission::query()->create(['agent_id' => $agent->getKey(), 'scope_type' => 'default', 'rate_percent' => 6.0]);
    TimberAgentCommission::query()->create(['agent_id' => $agent->getKey(), 'scope_type' => 'category', 'category' => 'Sboli legnami', 'rate_percent' => 8.0]);
    TimberAgentCommission::query()->create(['agent_id' => $agent->getKey(), 'scope_type' => 'product', 'product_id' => $product->getKey(), 'rate_percent' => 10.0]);

    expect(commission($agent, $product))->toBe(10.0);
});

it('falls back to the category rate when no product rule exists', function (): void {
    $agent = TimberAgent::factory()->create(['commission_percent' => 4.0]);
    $product = TimberProduct::factory()->create(['category' => 'Smokey Ben']);

    TimberAgentCommission::query()->create(['agent_id' => $agent->getKey(), 'scope_type' => 'default', 'rate_percent' => 6.0]);
    TimberAgentCommission::query()->create(['agent_id' => $agent->getKey(), 'scope_type' => 'category', 'category' => 'Smokey Ben', 'rate_percent' => 8.0]);

    expect(commission($agent, $product))->toBe(8.0);
});

it('falls back to the default rule, then the agent base commission', function (): void {
    $agent = TimberAgent::factory()->create(['commission_percent' => 4.5]);
    $product = TimberProduct::factory()->create(['category' => 'Altro']);

    expect(commission($agent, $product))->toBe(4.5); // no rules -> base

    TimberAgentCommission::query()->create(['agent_id' => $agent->getKey(), 'scope_type' => 'default', 'rate_percent' => 6.0]);

    expect(commission($agent->refresh(), $product))->toBe(6.0); // default rule wins over base
});
