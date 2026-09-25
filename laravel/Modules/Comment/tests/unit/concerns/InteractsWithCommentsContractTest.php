<?php

declare(strict_types=1);

use Modules\Comment\Tests\Fixtures\Concerns\InteractsWithCommentsContractStub;
use Modules\Comment\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('exposes InteractsWithComments for consumer modules', function (): void {
    $stub = new InteractsWithCommentsContractStub;
    $properties = $stub->commentatorProperties();

    Assert::assertSame('contract@example.com', $properties->email);
    Assert::assertSame('Contract', $properties->name);
});
