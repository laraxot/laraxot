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
    Assert::assertSame('pending', \Modules\Employee\Enums\AbsenceRequestStatusEnum::PENDING->value);
    Assert::assertSame('approved', \Modules\Employee\Enums\AbsenceRequestStatusEnum::APPROVED->value);
    Assert::assertSame('rejected', \Modules\Employee\Enums\AbsenceRequestStatusEnum::REJECTED->value);
});

test('absence request type constants are correct', function (): void {
    Assert::assertSame('vacation', \Modules\Employee\Enums\AbsenceRequestTypeEnum::VACATION->value);
    Assert::assertSame('leave', \Modules\Employee\Enums\AbsenceRequestTypeEnum::LEAVE->value);
    Assert::assertSame('sick', \Modules\Employee\Enums\AbsenceRequestTypeEnum::SICK->value);
    Assert::assertSame('injury', \Modules\Employee\Enums\AbsenceRequestTypeEnum::INJURY->value);
});
