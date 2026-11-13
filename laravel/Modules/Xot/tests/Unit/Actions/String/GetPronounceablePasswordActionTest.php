<?php

declare(strict_types=1);
use Modules\Xot\Actions\String\GetPronounceablePasswordAction;
use Modules\Xot\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

<<<<<<< HEAD
it('rispetta la lunghezza richiesta e le categorie di caratteri', function (int $length): void {
    $password = (new GetPronounceablePasswordAction)->execute($length);

    Assert::assertSame(max(5, $length), strlen($password));
    Assert::assertMatchesRegularExpression('/[a-z]/', $password);
    Assert::assertMatchesRegularExpression('/[A-Z]/', $password);
    Assert::assertMatchesRegularExpression('/[0-9]/', $password);
    Assert::assertMatchesRegularExpression('/[!#*_=+:?\-]/', $password);
    Assert::assertMatchesRegularExpression('/\A[a-zA-Z0-9!#*_=+:?\-]+\z/', $password);
})->with([-1, 0, 2, 4, 5, 6, 8, 12, 16, 64]);

it('genera dodici caratteri per impostazione predefinita', function (): void {
    Assert::assertSame(12, strlen((new GetPronounceablePasswordAction)->execute()));
=======
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
>>>>>>> 77b1e2748 (first)
});
