<?php

declare(strict_types=1);

uses(\Modules\Timber\Tests\TestCase::class);
use Modules\Timber\Models\TimberAgent;
use Modules\Timber\Models\TimberAgentCommission;
use Modules\Timber\Models\TimberProduct;
use Modules\Timber\Support\TimberCommissionResolver;

function commission(TimberAgent $agent, TimberProduct $product): float
{
    return app(TimberCommissionResolver::class)->rateFor($agent, $product);
}

it('prefers a product-specific commission over category, default and base', function (): void {
    $agent = TimberAgent::factory()->createOne(['commission_percent' => 4.0]);
    $product = TimberProduct::factory()->createOne(['category' => 'Sboli legnami']);

    TimberAgentCommission::query()->create(['agent_id' => $agent->getKey(), 'scope_type' => 'default', 'rate_percent' => 6.0]);
    TimberAgentCommission::query()->create(['agent_id' => $agent->getKey(), 'scope_type' => 'category', 'category' => 'Sboli legnami', 'rate_percent' => 8.0]);
    TimberAgentCommission::query()->create(['agent_id' => $agent->getKey(), 'scope_type' => 'product', 'product_id' => $product->getKey(), 'rate_percent' => 10.0]);

    expect(commission($agent, $product))->toBe(10.0);
});

it('falls back to the category rate when no product rule exists', function (): void {
    $agent = TimberAgent::factory()->createOne(['commission_percent' => 4.0]);
    $product = TimberProduct::factory()->createOne(['category' => 'Smokey Ben']);

    TimberAgentCommission::query()->create(['agent_id' => $agent->getKey(), 'scope_type' => 'default', 'rate_percent' => 6.0]);
    TimberAgentCommission::query()->create(['agent_id' => $agent->getKey(), 'scope_type' => 'category', 'category' => 'Smokey Ben', 'rate_percent' => 8.0]);

    expect(commission($agent, $product))->toBe(8.0);
});

it('falls back to the default rule, then the agent base commission', function (): void {
    $agent = TimberAgent::factory()->createOne(['commission_percent' => 4.5]);
    $product = TimberProduct::factory()->createOne(['category' => 'Altro']);

    expect(commission($agent, $product))->toBe(4.5); // no rules -> base

    TimberAgentCommission::query()->create(['agent_id' => $agent->getKey(), 'scope_type' => 'default', 'rate_percent' => 6.0]);

    expect(commission($agent->refresh(), $product))->toBe(6.0); // default rule wins over base
});
