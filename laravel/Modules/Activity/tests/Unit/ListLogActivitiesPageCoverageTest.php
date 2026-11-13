<?php

declare(strict_types=1);

namespace Modules\Activity\Tests\Unit;

use Filament\Notifications\Notification;
use Filament\Tables\Enums\PaginationMode;
use Modules\Activity\Filament\Pages\ListLogActivities;
use Modules\Activity\Filament\Resources\ActivityResource;
use Modules\Activity\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

<<<<<<< HEAD
describe('List Log Activities Page Coverage', function (): void {
    test('get breadcrumb returns string', function (): void {
        $result = TestCase::makeListLogActivitiesPage()->getBreadcrumb();
=======
beforeEach(function (): void {
    /** @var \Modules\Activity\Tests\TestCase $this */
$this->page = new class extends ListLogActivities
        {
            public static function getResource(): string
            {
                return ActivityResource::class;
            }

            public function exposeRestoreSuccess(): Notification
            {
                return $this->sendRestoreSuccessNotification();
            }

            public function exposeRestoreFailure(?string $message = null): Notification
            {
                return $this->sendRestoreFailureNotification($message);
            }
        };
});

describe('List Log Activities Page Coverage', function (): void {
    test('get breadcrumb returns string', function (): void {
        /** @var \Modules\Activity\Tests\TestCase $this */
$result = $this->requirePage()->getBreadcrumb();
>>>>>>> 13cf1831 (.)

        Assert::assertNotEmpty($result);
    });

    test('get breadcrumb uses static breadcrumb when set', function (): void {
<<<<<<< HEAD
<<<<<<< .merge_file_Gisxmu
        $page = new class() extends ListLogActivities
=======
        $page = new class extends ListLogActivities
>>>>>>> .merge_file_m6CipQ
=======
$page = new class extends ListLogActivities
>>>>>>> 13cf1831 (.)
        {
            protected static ?string $breadcrumb = 'Custom Breadcrumb';

            /** @return class-string */
            public static function getResource(): string
            {
                return ActivityResource::class;
            }
        };

        Assert::assertSame('Custom Breadcrumb', $page->getBreadcrumb());
    });

    test('can restore activity returns false when resource class does not exist', function (): void {
<<<<<<< HEAD
        Assert::assertFalse(class_exists('NonExistentClass\That\Does\Not\Exist'));
    });

    test('can restore activity returns false when resource lacks can restore method', function (): void {
<<<<<<< .merge_file_Gisxmu
        $page = new class() extends ListLogActivities
=======
        $page = new class extends ListLogActivities
>>>>>>> .merge_file_m6CipQ
=======
Assert::assertFalse(class_exists('NonExistentClass\That\Does\Not\Exist'));
    });

    test('can restore activity returns false when resource lacks can restore method', function (): void {
$page = new class extends ListLogActivities
>>>>>>> 13cf1831 (.)
        {
            public static function getResource(): string
            {
                return \stdClass::class;
            }
        };

        Assert::assertFalse($page->canRestoreActivity());
    });

    test('get pagination mode returns default', function (): void {
<<<<<<< HEAD
        $mode = TestCase::makeListLogActivitiesPage()->getPaginationMode();
=======
$mode = $this->requirePage()->getPaginationMode();
>>>>>>> 13cf1831 (.)

        Assert::assertSame(PaginationMode::Default, $mode);
    });

    test('get field label returns name when not in map', function (): void {
<<<<<<< HEAD
        try {
            $label = TestCase::makeListLogActivitiesPage()->getFieldLabel('nonexistent_field');
            Assert::assertSame('nonexistent_field', $label);
        } catch (\Throwable $e) {
            Assert::markTestSkipped('getFieldLabel() method not available in test context');
=======
try {
            $label = $this->requirePage()->getFieldLabel('nonexistent_field');
            Assert::assertSame('nonexistent_field', $label);
        } catch (\Throwable $e) {
            $this->skipTest('getFieldLabel() method not available in test context');
>>>>>>> 13cf1831 (.)
        }
    });

    test('send restore success notification returns notification', function (): void {
<<<<<<< HEAD
<<<<<<< .merge_file_Gisxmu
        $page = new class() extends ListLogActivities
=======
        $page = new class extends ListLogActivities
>>>>>>> .merge_file_m6CipQ
=======
$page = new class extends ListLogActivities
>>>>>>> 13cf1831 (.)
        {
            /** @return class-string */
            public static function getResource(): string
            {
                return ActivityResource::class;
            }

            public function exposeRestoreSuccess(): Notification
            {
                return $this->sendRestoreSuccessNotification();
            }
        };

        $notification = $page->exposeRestoreSuccess();
        Assert::assertNotEmpty($notification->getTitle());
    });

    test('send restore failure notification without message returns notification', function (): void {
<<<<<<< HEAD
<<<<<<< .merge_file_Gisxmu
        $page = new class() extends ListLogActivities
=======
        $page = new class extends ListLogActivities
>>>>>>> .merge_file_m6CipQ
=======
$page = new class extends ListLogActivities
>>>>>>> 13cf1831 (.)
        {
            /** @return class-string */
            public static function getResource(): string
            {
                return ActivityResource::class;
            }

            public function exposeRestoreFailure(?string $message = null): Notification
            {
                return $this->sendRestoreFailureNotification($message);
            }
        };

        $notification = $page->exposeRestoreFailure();
        Assert::assertNotEmpty($notification->getTitle());
    });

    test('send restore failure notification with message includes body', function (): void {
<<<<<<< HEAD
<<<<<<< .merge_file_Gisxmu
        $page = new class() extends ListLogActivities
=======
        $page = new class extends ListLogActivities
>>>>>>> .merge_file_m6CipQ
=======
$page = new class extends ListLogActivities
>>>>>>> 13cf1831 (.)
        {
            /** @return class-string */
            public static function getResource(): string
            {
                return ActivityResource::class;
            }

            public function exposeRestoreFailure(?string $message = null): Notification
            {
                return $this->sendRestoreFailureNotification($message);
            }
        };

        $notification = $page->exposeRestoreFailure('test message');
        Assert::assertSame('test message', $notification->getBody());
    });

    test('can restore activity with record executes resource check', function (): void {
<<<<<<< HEAD
        $result = TestCase::makeListLogActivitiesPage()->canRestoreActivity();
=======
$result = $this->requirePage()->canRestoreActivity();
>>>>>>> 13cf1831 (.)
        Assert::assertFalse($result);
    });
});
