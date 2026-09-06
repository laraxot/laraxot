<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\Wts\Filament\Pages\WtsAuditPage;
use Modules\Wts\Filament\Pages\WtsDashboard;
use Modules\Wts\Filament\Pages\WtsGrindingWashingPage;
use Modules\Wts\Filament\Pages\WtsIngressesPage;
use Modules\Wts\Filament\Pages\WtsInventoryPage;
use Modules\Wts\Filament\Pages\WtsOperationalParametersPage;
use Modules\Wts\Filament\Pages\WtsOutputsPage;
use Modules\Wts\Filament\Pages\WtsPressingPage;
use Modules\Wts\Filament\Pages\WtsPrintingPage;
use Modules\Wts\Filament\Pages\WtsProcessingPage;
use Modules\Wts\Filament\Pages\WtsProcessingSheetPage;
use Modules\Wts\Filament\Pages\WtsReportsPage;
use Modules\Wts\Filament\Pages\WtsSortingPage;
use Modules\Wts\Filament\Pages\WtsUnpackagingPage;
use Modules\Wts\Filament\Pages\WtsWorkQueuePage;
use Modules\User\Models\User;
use App\Support\AccountFeatures;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

/**
 * Modalita Brondi: il wts_operator vede solo lo snodo lavorazioni e le quattro
 * pagine di lavorazione, da selezione e cernita fino a pressatura.
 */
function brondiOperator(): User
{
    Role::findOrCreate('wts_operator');
    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_operator']);

    return $user;
}

it('lascia allo snodo e alle quattro lavorazioni', function (string $page): void {
    $user = brondiOperator();

    expect(AccountFeatures::canAccessWtsPage($user, $page))->toBeTrue();
})->with([
    'snodo lavorazioni' => WtsProcessingPage::class,
    'selezione e cernita' => WtsSortingPage::class,
    'sconfezionamento' => WtsUnpackagingPage::class,
    'triturazione e lavaggio' => WtsGrindingWashingPage::class,
    'pressatura' => WtsPressingPage::class,
]);

it('tiene fuori tutte le altre pagine del pannello', function (string $page): void {
    $user = brondiOperator();

    expect(AccountFeatures::canAccessWtsPage($user, $page))->toBeFalse();
})->with([
    'dashboard' => WtsDashboard::class,
    'ingressi' => WtsIngressesPage::class,
    'giacenze' => WtsInventoryPage::class,
    'uscite' => WtsOutputsPage::class,
    'stampe' => WtsPrintingPage::class,
    'coda di lavoro' => WtsWorkQueuePage::class,
    'foglio lavorazione' => WtsProcessingSheetPage::class,
    'audit' => WtsAuditPage::class,
]);

it('riserva report e parametri operativi al solo wts_admin', function (): void {
    $operator = brondiOperator();

    actingAs($operator);
    expect(WtsReportsPage::canAccess())->toBeFalse();
    expect(WtsOperationalParametersPage::canAccess())->toBeFalse();

    Role::findOrCreate('wts_admin');
    $admin = \Modules\Wts\Tests\TestCase::createUser();
    $admin->syncRoles(['wts_admin']);

    actingAs($admin);
    expect(WtsReportsPage::canAccess())->toBeTrue();
    expect(WtsOperationalParametersPage::canAccess())->toBeTrue();
});

it('nello snodo mostra i quattro tasti ma non i passi successivi', function (): void {
    $user = brondiOperator();

    actingAs($user);
    get('/wts/processing')
        ->assertOk()
        ->assertSee('Selezione e cernita')
        ->assertSee('Sconfezionamento')
        ->assertSee('Triturazione e lavaggio')
        ->assertSee('Pressatura')
        // I passi successivi portano a pagine che l'operatore non puo aprire:
        // mostrarli significherebbe offrirgli link che finiscono in accesso negato.
        ->assertDontSee('Passi successivi')
        ->assertDontSee('Apri foglio lavorazione')
        ->assertDontSee('Apri report finale');
});

it('all amministratore lascia invece i passi successivi', function (): void {
    Role::findOrCreate('wts_admin');
    $admin = \Modules\Wts\Tests\TestCase::createUser();
    $admin->syncRoles(['wts_admin']);

    actingAs($admin);
    get('/wts/processing')
        ->assertOk()
        ->assertSee('Passi successivi')
        ->assertSee('Apri foglio lavorazione')
        ->assertSee('Apri report finale');
});
