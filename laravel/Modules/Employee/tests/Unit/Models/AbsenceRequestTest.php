<?php

declare(strict_types=1);

namespace Modules\Employee\Tests\Unit\Models;

use Modules\Employee\Models\AbsenceRequest;
use PHPUnit\Framework\Assert;

test('absence request casts datetime attributes', function (): void {
    $casts = (new AbsenceRequest())->getCasts();

    Assert::assertSame('datetime', $casts['starts_at']);
    Assert::assertSame('datetime', $casts['ends_at']);
    Assert::assertSame('datetime', $casts['decided_at']);
});

test('absence request has expected fillable attributes', function (): void {
    $fillable = (new AbsenceRequest())->getFillable();

    Assert::assertContains('user_id', $fillable);
    Assert::assertContains('type', $fillable);
    Assert::assertContains('starts_at', $fillable);
    Assert::assertContains('ends_at', $fillable);
    Assert::assertContains('status', $fillable);
    Assert::assertContains('decided_by_user_id', $fillable);
    Assert::assertContains('decided_at', $fillable);
});

test('absence request status constants are correct', function (): void {
    Assert::assertSame('pending', AbsenceRequest::STATUS_PENDING);
    Assert::assertSame('approved', AbsenceRequest::STATUS_APPROVED);
    Assert::assertSame('rejected', AbsenceRequest::STATUS_REJECTED);
});

test('absence request type constants are correct', function (): void {
    Assert::assertSame('vacation', AbsenceRequest::TYPE_VACATION);
    Assert::assertSame('leave', AbsenceRequest::TYPE_LEAVE);
    Assert::assertSame('sick', AbsenceRequest::TYPE_SICK);
    Assert::assertSame('injury', AbsenceRequest::TYPE_INJURY);
});
