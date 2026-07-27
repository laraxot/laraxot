<?php

declare(strict_types=1);

use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;
use Modules\WhatsApp\Filament\Pages\WhatsAppInboxPage;
use Modules\WhatsApp\Tests\TestCase;
use PHPUnit\Framework\Assert;

/**
 * Helper Pest/PHPStan — modulo WhatsApp.
 *
 * @see Modules/Platform/tests/PestHelpers.php
 */
function whatsAppTest(): TestCase
{
    Assert::assertNotNull(TestCase::$currentTest);

    return TestCase::$currentTest;
}

/**
 * @param  array<string, mixed>  $attributes
 */
function whatsAppCreateUser(array $attributes = []): User
{
    return UserFactory::new()->createOne($attributes);
}

function whatsAppUserId(): int
{
    return whatsAppTest()->userId;
}

/**
 * @return Testable<WhatsAppInboxPage>
 */
function whatsAppInboxComponent(): Testable
{
    return Livewire::test(WhatsAppInboxPage::class);
}
