<?php

declare(strict_types=1);

namespace Modules\Job\Tests\Unit;

use Mockery;
use Mockery\MockInterface;
use Modules\Job\Actions\Command\GetCommandsAction;
use Modules\Job\Datas\CommandData;
use Modules\Job\Filament\Resources\ScheduleResource\Schemas\ScheduleForm;
use Modules\Job\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Spatie\LaravelData\DataCollection;

uses(\Modules\Job\Tests\TestCase::class)->group('no-job-db');

afterEach(function (): void {
    Mockery::close();
    $ref = new \ReflectionClass(ScheduleForm::class);
    if ($ref->hasProperty('commands')) {
        $prop = $ref->getProperty('commands');
        $prop->setAccessible(true);
        $prop->setValue(null, null);
    }
});

describe('ScheduleForm coverage', function (): void {
    test('getFormSchema espone section e campi schedule', function (): void {
        $commands = new DataCollection(CommandData::class, [
            CommandData::from([
                'name' => 'inspire',
                'description' => 'Display an inspiring quote',
                'signature' => 'inspire',
                'full_name' => 'inspire',
                'arguments' => [],
                'options' => ['withValue' => []],
            ]),
        ]);

        /** @var MockInterface&GetCommandsAction $action */
        $action = Mockery::mock(GetCommandsAction::class);
        expectMethod($action, 'execute')->andReturn($commands);
        app()->instance(GetCommandsAction::class, $action);

        $schema = ScheduleForm::getFormSchema();
        Assert::assertArrayHasKey('main_section', $schema);
        Assert::assertNotEmpty($schema);
    });
});
