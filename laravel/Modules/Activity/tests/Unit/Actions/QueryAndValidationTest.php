<?php

declare(strict_types=1);

namespace Modules\Activity\Tests\Unit\Actions;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Mockery;
use Modules\Activity\Actions\ActivityLogger as ActivityLoggerAction;
use Modules\Activity\Actions\LogActivityAction;
use Modules\Activity\Actions\Query\GetActivitiesByTypeAction;
use Modules\Activity\Actions\Query\GetRecentActivitiesAction;
use Modules\Activity\Actions\Query\GetSubjectActivityLogAction;
use Modules\Activity\Actions\Query\GetUserActivitiesAction;
use Modules\Activity\Actions\RecordSubjectActivityAction;
use Modules\Activity\Actions\RestoreActivityAction;
use Modules\Activity\Adapters\ActivityLogger as ActivityLoggerAdapter;
use Modules\Activity\Adapters\ActivityRecorder;
use Modules\User\Models\User;
use PHPUnit\Framework\Assert;
use Webmozart\Assert\InvalidArgumentException as AssertInvalidArgumentException;

describe('Query Actions validation', function (): void {
    test('GetRecentActivitiesAction rifiuta limit non positivo', function (): void {
        expect(fn (): mixed => (new GetRecentActivitiesAction())->execute(0))
            ->toThrow(InvalidArgumentException::class, 'Limit must be positive');
    });

    test('GetUserActivitiesAction rifiuta limit non positivo', function (): void {
        expect(fn (): mixed => (new GetUserActivitiesAction())->execute(new User(), -1))
            ->toThrow(InvalidArgumentException::class);
    });

    test('GetActivitiesByTypeAction rifiuta type vuoto e limit invalido', function (): void {
        $action = new GetActivitiesByTypeAction();

        expect(fn (): mixed => $action->execute(''))
            ->toThrow(InvalidArgumentException::class, 'Type cannot be empty');

        expect(fn (): mixed => $action->execute('login', 0))
            ->toThrow(InvalidArgumentException::class, 'Limit must be positive');
    });
});

describe('ActivityLogger Action validation', function (): void {
    test('getRecent getUserActivities getByType cleanOld validano input', function (): void {
        $logger = new ActivityLoggerAction();
        $user = new User();

        expect(fn (): mixed => $logger->getRecent(0))
            ->toThrow(InvalidArgumentException::class);

        expect(fn (): mixed => $logger->getUserActivities($user, 0))
            ->toThrow(InvalidArgumentException::class);

        expect(fn (): mixed => $logger->getByType('', 10))
            ->toThrow(InvalidArgumentException::class, 'Type cannot be empty');

        expect(fn (): mixed => $logger->getByType('login', -2))
            ->toThrow(InvalidArgumentException::class);

        expect(fn (): mixed => $logger->cleanOld(0))
            ->toThrow(InvalidArgumentException::class, 'Days must be positive');
    });
});

test('LogActivityAction execute rifiuta user non User', function (): void {
    $subject = new class() extends Model
    {
        protected $table = 'stub_models';
    };
    $invalidUser = new class() extends Model
    {
        protected $table = 'users';
    };

    $action = new LogActivityAction(type: 'test', user: $invalidUser, subject: $subject);

    expect(fn (): mixed => $action->execute())
        ->toThrow(InvalidArgumentException::class, 'User must be an instance of User');
});

describe('ActivityLogger Adapter validation', function (): void {
    test('log rifiuta user non User', function (): void {
        $logger = new ActivityLoggerAdapter();

        expect(fn (): mixed => $logger->log('event', new \stdClass()))
            ->toThrow(InvalidArgumentException::class, 'User must be an instance of User');
    });

    test('getRecent delega validazione limit', function (): void {
        expect(fn (): mixed => (new ActivityLoggerAdapter())->getRecent(0))
            ->toThrow(InvalidArgumentException::class);
    });
});

describe('ActivityRecorder Adapter', function (): void {
    test('record delega a RecordSubjectActivityAction', function (): void {
        $mock = Mockery::mock(RecordSubjectActivityAction::class);
        mockeryExpect($mock->shouldReceive('execute'))
            ->once()
            ->with(User::class, 42, 'updated', ['name' => 'x'], null);
        app()->instance(RecordSubjectActivityAction::class, $mock);

        (new ActivityRecorder())->record(User::class, 42, 'updated', ['name' => 'x']);

    });

    test('getLog delega a GetSubjectActivityLogAction', function (): void {
        $mock = Mockery::mock(GetSubjectActivityLogAction::class);
        mockeryExpect($mock->shouldReceive('execute'))
            ->once()
            ->with(User::class, 7)
            ->andReturn([['id' => 1]]);
        app()->instance(GetSubjectActivityLogAction::class, $mock);

        $log = (new ActivityRecorder())->getLog(User::class, 7);

        Assert::assertSame([['id' => 1]], $log);
    });
});

describe('RestoreActivityAction validation', function (): void {
    test('execute rifiuta oldProperties vuote', function (): void {
        $model = new class() extends Model
        {
            protected $table = 'stub_models';
        };

        expect(fn () => (new RestoreActivityAction())->execute($model, []))
            ->toThrow(AssertInvalidArgumentException::class);
    });
});
