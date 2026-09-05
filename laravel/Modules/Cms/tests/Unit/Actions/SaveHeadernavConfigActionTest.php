<?php

declare(strict_types=1);

use Modules\Cms\Actions\SaveHeadernavConfigAction;
use Modules\Cms\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);
test('SaveHeadernavConfigAction can be instantiated', function () {
    $action = new SaveHeadernavConfigAction();

    Assert::assertInstanceOf(SaveHeadernavConfigAction::class, $action);
});

test('SaveHeadernavConfigAction execute method exists', function () {
    $action = new SaveHeadernavConfigAction();
});
