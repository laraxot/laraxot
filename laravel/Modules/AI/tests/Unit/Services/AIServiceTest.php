<?php

declare(strict_types=1);

namespace Modules\AI\Tests\Unit\Services;

use Modules\AI\Actions\CompletionAction;
use PHPUnit\Framework\Assert;

describe('CompletionAction', function (): void {
    test('_action_can_be_instantiated', function (): void {
        $action = new CompletionAction();

        Assert::assertInstanceOf(CompletionAction::class, $action);
    });
});
