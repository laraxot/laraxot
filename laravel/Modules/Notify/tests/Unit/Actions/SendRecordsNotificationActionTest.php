<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Actions;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Modules\Notify\Actions\SendRecordNotificationAction;
use Modules\Notify\Actions\SendRecordsNotificationAction;
use Modules\Notify\Tests\Fixtures\SendRecordNotificationFailStub;
use Modules\Notify\Tests\Fixtures\SendRecordNotificationNoopStub;
use Modules\Notify\Tests\Fixtures\SendRecordNotificationThrowStub;
use Modules\Notify\Tests\Fixtures\SendRecordsNotificationRecordDummy;
use Modules\Notify\Tests\Fixtures\SendRecordsSafeEloquentCastEmptyStub;
use Modules\Notify\Tests\Fixtures\SendRecordsSafeEloquentCastStub;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Actions\Cast\SafeEloquentCastAction;
use PHPUnit\Framework\Assert;

=======
use Modules\Notify\Tests\TestCase;
use Modules\Xot\Actions\Cast\SafeEloquentCastAction;
use PHPUnit\Framework\Assert;

uses(TestCase::class)->group('no-notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use Modules\Xot\Actions\Cast\SafeEloquentCastAction;
use PHPUnit\Framework\Assert;

>>>>>>> a988596b (first)
/**
 * @param  array<string, mixed>  $attributes
 */
function makeDummyBulkNotifyRecord(array $attributes = []): Model
{
    return new SendRecordsNotificationRecordDummy($attributes);
}

test('send records notification action counts successful sends', function (): void {
<<<<<<< HEAD
    app()->instance(SafeEloquentCastAction::class, new SendRecordsSafeEloquentCastStub);
    app()->instance(SendRecordNotificationAction::class, new SendRecordNotificationNoopStub);

    $records = new EloquentCollection([
        makeDummyBulkNotifyRecord(['id' => 1, 'name' => 'Alpha']),
<<<<<<< HEAD
        makeDummyBulkNotifyRecord(['id' => 2, 'name' => 'Beta'])]);
=======
        makeDummyBulkNotifyRecord(['id' => 2, 'name' => 'Beta']),
    ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    app()->instance(SafeEloquentCastAction::class, new SendRecordsSafeEloquentCastStub());
    app()->instance(SendRecordNotificationAction::class, new SendRecordNotificationNoopStub());

    $records = new EloquentCollection([
        makeDummyBulkNotifyRecord(['id' => 1, 'name' => 'Alpha']),
        makeDummyBulkNotifyRecord(['id' => 2, 'name' => 'Beta'])]);
>>>>>>> a988596b (first)

    $result = app(SendRecordsNotificationAction::class)->execute(
        records: $records,
        templateSlug: 'welcome-template',
        channels: ['mail', 'sms'],
    );

    Assert::assertSame(4, $result->successCount);
    Assert::assertSame(0, $result->errorCount);
    Assert::assertSame(0, $result->errors->count());
    Assert::assertSame(4, $result->totalProcessed);
});

test('send records notification action accumulates errors per channel', function (): void {
<<<<<<< HEAD
    app()->instance(SafeEloquentCastAction::class, new SendRecordsSafeEloquentCastStub);
    app()->instance(SendRecordNotificationAction::class, new SendRecordNotificationFailStub);

    $records = new EloquentCollection([
        makeDummyBulkNotifyRecord(['id' => 1, 'name' => 'Ok Record', 'should_fail' => false]),
<<<<<<< HEAD
        makeDummyBulkNotifyRecord(['id' => 2, 'name' => 'Fail Record', 'should_fail' => true])]);
=======
        makeDummyBulkNotifyRecord(['id' => 2, 'name' => 'Fail Record', 'should_fail' => true]),
    ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    app()->instance(SafeEloquentCastAction::class, new SendRecordsSafeEloquentCastStub());
    app()->instance(SendRecordNotificationAction::class, new SendRecordNotificationFailStub());

    $records = new EloquentCollection([
        makeDummyBulkNotifyRecord(['id' => 1, 'name' => 'Ok Record', 'should_fail' => false]),
        makeDummyBulkNotifyRecord(['id' => 2, 'name' => 'Fail Record', 'should_fail' => true])]);
>>>>>>> a988596b (first)

    $result = app(SendRecordsNotificationAction::class)->execute(
        records: $records,
        templateSlug: 'welcome-template',
        channels: ['mail', 'sms'],
    );

    Assert::assertSame(2, $result->successCount);
    Assert::assertSame(2, $result->errorCount);
    Assert::assertSame(2, $result->errors->count());
    Assert::assertSame('Fail Record', $result->errors->first()['record'] ?? null);
    Assert::assertSame(4, $result->totalProcessed);
});

test('send records notification action falls back to record key when name is missing', function (): void {
<<<<<<< HEAD
    app()->instance(SafeEloquentCastAction::class, new SendRecordsSafeEloquentCastEmptyStub);
    app()->instance(SendRecordNotificationAction::class, new SendRecordNotificationThrowStub);
=======
    app()->instance(SafeEloquentCastAction::class, new SendRecordsSafeEloquentCastEmptyStub());
    app()->instance(SendRecordNotificationAction::class, new SendRecordNotificationThrowStub());
>>>>>>> a988596b (first)

    $record = makeDummyBulkNotifyRecord(['id' => 99, 'should_fail' => true]);
    $records = new EloquentCollection([$record]);

    $result = app(SendRecordsNotificationAction::class)->execute(
        records: $records,
        templateSlug: 'welcome-template',
        channels: ['mail'],
    );

    Assert::assertSame(0, $result->successCount);
    Assert::assertSame(1, $result->errorCount);
    Assert::assertSame('99', $result->errors->first()['record'] ?? null);
});
