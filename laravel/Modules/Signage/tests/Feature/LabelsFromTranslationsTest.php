<?php

declare(strict_types=1);

use Livewire\Livewire;
use Modules\Signage\Database\Seeders\SignWorkCenterSeeder;
use Modules\Signage\Filament\Resources\SignWorkCenterResource\Pages\ListSignWorkCenters;
use Modules\Signage\Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;
use PHPUnit\Framework\Assert;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

uses(TestCase::class);

/**
 * Le intestazioni arrivano dal file di traduzione tramite il LangServiceProvider,
 * non da un ->label() scritto a mano: se qualcuno svuota sign_work_center.php
 * la tabella mostrerebbe i nomi dei campi e questo test lo direbbe.
 */
it('takes column headings from the translation file', function (): void {
    $admin = UserFactory::new()->createOne();
    Assert::assertInstanceOf(User::class, $admin);
    $admin->syncRoles([Role::findOrCreate('admin')]);
    actingAs($admin);
    filament()->setCurrentPanel('admin');

    (new SignWorkCenterSeeder())->run();

    Livewire::test(ListSignWorkCenters::class)
        ->assertSuccessful()
        ->assertSee('Codice')
        ->assertSee('Descrizione')
        ->assertSee('Interno');
});
