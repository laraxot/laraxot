<?php

declare(strict_types=1);

namespace Modules\Platform\Database\Seeders;

use Modules\Platform\ValueObjects\CompanyProfile;

/**
 * Bootstrap istanza per BOUTIQUE IDRAULICA S.R.L. (Rimini) — termoidraulica.
 * Password titolare da env BOUTIQUE_ADMIN_PASSWORD.
 */
final class BoutiqueIdraulicaBootstrapSeeder extends ThermoInstanceBootstrapSeeder
{
    protected function ownerEmail(): string
    {
        return 'boutique.idraulica@gmail.com';
    }

    protected function ownerName(): string
    {
        return 'Boutique Idraulica';
    }

    protected function passwordEnvKey(): string
    {
        return 'BOUTIQUE_ADMIN_PASSWORD';
    }

    protected function companyProfile(): CompanyProfile
    {
        return new CompanyProfile(
            legalName: 'Boutique Idraulica S.r.l.',
            vatNumber: 'IT04655810408',
            taxCode: '04655810408',
            taxRegime: 'RF01', // ordinario (societa di capitali)
            street: 'Via Marecchiese 273',
            postalCode: '47922',
            city: 'Rimini',
            province: 'RN',
            country: 'Italia',
            email: 'boutique.idraulica@gmail.com',
            phone: '',
            logoPath: null,
        );
    }
}
