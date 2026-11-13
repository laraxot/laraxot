<?php

declare(strict_types=1);

namespace Modules\UI\Tests\Unit;

<<<<<<< HEAD
=======
use Mockery;
>>>>>>> f5ffd8b4 (.)
use Mockery\MockInterface;
use Modules\UI\Tests\Fixtures\UiBasePolicyBehaviorConcretePolicy;
use Modules\UI\Tests\TestCase;
use Modules\Xot\Contracts\UserContract;
use PHPUnit\Framework\Assert;

uses(TestCase::class)->group('no-ui-db');

/**
<<<<<<< HEAD
 * @param list<string> $roles
 *
=======
 * @param  list<string>  $roles
>>>>>>> f5ffd8b4 (.)
 * @return MockInterface&UserContract
 */
function uiBehaviorUser(array $roles = []): UserContract
{
    /** @var MockInterface&UserContract $user */
<<<<<<< HEAD
    $user = \Mockery::mock(UserContract::class);
=======
    $user = Mockery::mock(UserContract::class);
>>>>>>> f5ffd8b4 (.)
    TestCase::expectMethod($user, 'hasRole')
        ->andReturnUsing(static function (array|string $richiesti) use ($roles): bool {
            /** @var list<string> $normalizzati */
            $normalizzati = is_array($richiesti) ? $richiesti : [$richiesti];

<<<<<<< HEAD
            return [] !== array_intersect($normalizzati, $roles);
=======
            return array_intersect($normalizzati, $roles) !== [];
>>>>>>> f5ffd8b4 (.)
        });

    return $user;
}

afterEach(function (): void {
<<<<<<< HEAD
    \Mockery::close();
});

test('UiBasePolicy before concede super-admin e ritorna null altrimenti', function (): void {
    $policy = new UiBasePolicyBehaviorConcretePolicy();
=======
    Mockery::close();
});

test('UiBasePolicy before concede super-admin e ritorna null altrimenti', function (): void {
    $policy = new UiBasePolicyBehaviorConcretePolicy;
>>>>>>> f5ffd8b4 (.)
    $super = uiBehaviorUser(['super-admin']);

    Assert::assertTrue($policy->before($super, 'viewAny'));
    Assert::assertNull($policy->before(uiBehaviorUser(), 'viewAny'));
});
