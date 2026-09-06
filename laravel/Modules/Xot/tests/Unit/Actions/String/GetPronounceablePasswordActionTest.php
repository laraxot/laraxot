<?php

declare(strict_types=1);

use Modules\Xot\Actions\String\GetPronounceablePasswordAction;
use Modules\Xot\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

it('generates pronounceable password correctly', function (): void {
    $action = app(GetPronounceablePasswordAction::class);

    $password = $action->execute(12);

    Assert::assertGreaterThanOrEqual(8, strlen($password)); // min length logic inside
    Assert::assertMatchesRegularExpression('/[0-9]/', (string) $password); // contains digit
    Assert::assertMatchesRegularExpression('/[!#*-_=+:?]/', (string) $password); // contains special
    Assert::assertMatchesRegularExpression('/[A-Z]/', (string) $password); // contains uppercase
});

it('handles small length correctly', function (): void {
    $action = app(GetPronounceablePasswordAction::class);
    $password = $action->execute(2);
    Assert::assertGreaterThanOrEqual(4, strlen($password));
});
