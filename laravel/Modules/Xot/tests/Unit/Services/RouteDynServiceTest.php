<?php

declare(strict_types=1);

use Modules\Xot\Services\RouteDynService;
use PHPUnit\Framework\Assert;

test('RouteDynService getMethod filtra valori non stringa e reindicizza', function (): void {
    Assert::assertSame(
        ['get', 'post'],
        RouteDynService::getMethod(['method' => ['get', 1, 'post']], null),
    );
});

test('RouteDynService getMethod torna al default se non restano metodi validi', function (): void {
    Assert::assertSame(['get', 'post'], RouteDynService::getMethod(['method' => [1, false]], null));
});
