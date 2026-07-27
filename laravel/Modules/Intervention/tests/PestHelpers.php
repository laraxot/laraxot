<?php

declare(strict_types=1);

use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Modules\Intervention\Filament\Pages\InterventionCalendar;
use Modules\Intervention\Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;
use PHPUnit\Framework\Assert;

function interventionTest(): TestCase
{
    Assert::assertNotNull(TestCase::$currentTest);

    return TestCase::$currentTest;
}

/**
 * @param  array<string, mixed>  $attributes
 */
function interventionUser(array $attributes = []): User
{
    return UserFactory::new()->createOne($attributes);
}

/**
 * @return Testable<InterventionCalendar>
 */
function calendarComponent(): Testable
{
    return Livewire::test(InterventionCalendar::class);
}
