<?php

declare(strict_types=1);

use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;
use Modules\WhatsApp\Filament\Pages\WhatsAppInboxPage;
use Modules\WhatsApp\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Spatie\Permission\Models\Role;

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
    // canAccess() richiede la feature admin.whatsapp_ai (Gate manage-whatsapp-ai)
    // e un panel con semantica admin: autentichiamo un admin abilitato via matrix.
    $email = 'whatsapp-admin@test.local';
    $accounts = (array) config('account_features.accounts', []);
    $accounts[$email]['features']['admin.whatsapp_ai'] = true;
    config(['account_features.accounts' => $accounts]);

    Role::findOrCreate('admin');
    $user = UserFactory::new()->createOne(['email' => $email]);
    $user->syncRoles(['admin']);

    filament()->setCurrentPanel('whatsapp::admin');

    return Livewire::actingAs($user)->test(WhatsAppInboxPage::class);
}
