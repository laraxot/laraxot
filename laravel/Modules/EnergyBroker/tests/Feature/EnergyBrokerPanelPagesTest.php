<?php

declare(strict_types=1);

use Modules\EnergyBroker\Filament\Pages\EnergyBrokerAgentPortalPage;
use Modules\EnergyBroker\Filament\Pages\EnergyBrokerCommissionsPage;
use Modules\EnergyBroker\Filament\Pages\EnergyBrokerCompliancePage;
use Modules\EnergyBroker\Filament\Pages\EnergyBrokerCrmPage;
use Modules\EnergyBroker\Filament\Pages\EnergyBrokerCustomerCarePage;
use Modules\EnergyBroker\Filament\Pages\EnergyBrokerDashboard;
use Modules\EnergyBroker\Filament\Pages\EnergyBrokerOfferComparatorPage;
use Modules\EnergyBroker\Filament\Pages\EnergyBrokerPipelinePage;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use PHPUnit\Framework\Assert;

use function Pest\Laravel\get;

/*
 * Le pagine del pannello si dichiarano non scopribili e vengono montate a mano
 * dal PanelProvider: senza un test che le apra davvero, una pagina dimenticata
 * nell'elenco resta un 404 che nessuno nota fino alla demo.
 */
uses(\Modules\EnergyBroker\Tests\TestCase::class);

function energyBrokerUser(string $role = 'energy_broker_admin'): User
{
    Role::findOrCreate($role);
    $user = UserFactory::new()->createOne();
    // 'energy-broker' e' l'id del pannello, ed e' il ruolo che ne apre la porta;
    // $role dice invece cosa l'utente puo' fare una volta dentro.
    $user->syncRoles(['energy-broker', $role]);

    return $user;
}

it('opens every page of the panel for an energy broker admin', function (string $path): void {
    actingAs(energyBrokerUser());
    get($path)->assertOk();
})->with([
    '/energy-broker',
    '/energy-broker/crm',
    '/energy-broker/document-acquisition',
    '/energy-broker/offers',
    '/energy-broker/pipeline',
    '/energy-broker/backoffice-compliance',
    '/energy-broker/commissions',
    '/energy-broker/customer-care',
    '/energy-broker/agent-portal',
]);

it('registers each mounted page in the panel', function (string $page): void {
    Assert::assertTrue(class_exists($page));
    /** @var class-string<\Filament\Pages\Page> $page */
    Assert::assertNotEmpty($page::getUrl(panel: 'energy-broker'));
})->with([
    EnergyBrokerDashboard::class,
    EnergyBrokerCrmPage::class,
    EnergyBrokerOfferComparatorPage::class,
    EnergyBrokerPipelinePage::class,
    EnergyBrokerCompliancePage::class,
    EnergyBrokerCommissionsPage::class,
    EnergyBrokerCustomerCarePage::class,
    EnergyBrokerAgentPortalPage::class,
]);

it('keeps the panel closed to a user without the broker role', function (): void {
    Role::findOrCreate('operator');
    $outsider = UserFactory::new()->createOne();
    $outsider->syncRoles(['operator']);

    actingAs($outsider);
    get('/energy-broker')->assertForbidden();
});

it('denies a guest', function (): void {
    get('/energy-broker')->assertRedirect();
});
