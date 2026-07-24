<?php

declare(strict_types=1);

namespace Modules\Platform\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Platform\Company\CompanyProfileProvider;
use Modules\Platform\Models\PlatformSetting;
use Modules\Platform\ValueObjects\CompanyProfile;
use Modules\User\Models\User;
use Spatie\Permission\Models\Role;

/**
 * Bootstrap PULITO (produzione) di un'istanza per un termoidraulico sulla verticale "thermo".
 *
 * Crea: ruoli thermo, utente titolare (thermo_admin + admin, approvato), anagrafica azienda
 * emittente in "company.profile" (letta dai PDF di preventivo/fattura) e disabilita la
 * fattura elettronica finché non configurano il provider. NON semina dati finti.
 *
 * Idempotente. La password si passa da env (vedi passwordEnvKey()).
 */
abstract class ThermoInstanceBootstrapSeeder extends Seeder
{
    abstract protected function ownerEmail(): string;

    abstract protected function ownerName(): string;

    abstract protected function passwordEnvKey(): string;

    abstract protected function companyProfile(): CompanyProfile;

    public function run(): void
    {
        foreach (['thermo_admin', 'thermo_technician', 'thermo_customer', 'admin'] as $role) {
            Role::findOrCreate($role);
        }

        $envPassword = getenv($this->passwordEnvKey());
        $password = is_string($envPassword) && trim($envPassword) !== '' ? $envPassword : 'CambiaSubito!123';

        $owner = User::query()->firstOrCreate(
            ['email' => $this->ownerEmail()],
            ['name' => $this->ownerName(), 'password' => Hash::make($password), 'approved_at' => now()],
        );
        $owner->forceFill(['approved_at' => $owner->approved_at ?? now()])->save();
        $owner->syncRoles(['thermo_admin', 'admin']);

        $profile = $this->companyProfile();

        PlatformSetting::query()->updateOrCreate(
            ['key' => CompanyProfileProvider::SETTINGS_KEY],
            ['value_json' => $profile->toArray(), 'updated_by_user_id' => $owner->id],
        );

        PlatformSetting::query()->updateOrCreate(
            ['key' => 'billing.einvoice.provider'],
            ['value_json' => ['value' => 'disabled'], 'updated_by_user_id' => $owner->id],
        );

        $this->command?->info(sprintf(
            'Bootstrap "%s" OK: utente #%d (%s) con ruoli thermo_admin+admin. Cambiare la password al primo accesso.',
            $profile->legalName,
            (int) $owner->id,
            (string) $owner->email,
        ));
    }
}
