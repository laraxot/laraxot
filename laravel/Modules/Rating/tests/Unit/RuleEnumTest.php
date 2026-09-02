<?php

declare(strict_types=1);

namespace Modules\Rating\Tests\Unit;

use Modules\Rating\Enums\RuleEnum;
use Modules\Rating\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('RuleEnum espone le regole di validazione attese', function (): void {
    Assert::assertSame('', RuleEnum::Null->value);
    Assert::assertSame('numeric|min:0|max:5', RuleEnum::ZeroFive->value);
    Assert::assertSame('min:0|max:25|not_in:1,2,3', RuleEnum::ZeroOrMin4Max25->value);
    Assert::assertSame('nullable|numeric|min:0|max:25', RuleEnum::NullableNumericMin0Max25->value);
});
