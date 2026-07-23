<?php

declare(strict_types=1);

namespace Modules\Platform\Database\Seeders;

use Modules\Platform\ValueObjects\CompanyProfile;

/**
 * Bootstrap istanza per DE LUCA FRANCESCO (Pianiga, VE) — termoidraulica, ditta individuale.
 * Password titolare da env DELUCA_ADMIN_PASSWORD.
 */
final class DeLucaBootstrapSeeder extends ThermoInstanceBootstrapSeeder
{
    protected function ownerEmail(): string
    {
        return 'Delucaernesto9@gmail.com';
    }

    protected function ownerName(): string
    {
        return 'De Luca Francesco';
    }

    protected function passwordEnvKey(): string
    {
        return 'DELUCA_ADMIN_PASSWORD';
    }

    protected function companyProfile(): CompanyProfile
    {
        return new CompanyProfile(
            legalName: 'De Luca Francesco',
            vatNumber: 'IT03185140617',
            taxCode: '', // C.F. personale non disponibile: da completare
            taxRegime: 'RF19', // forfettario (L. 190/2014, art. 1 c. 54-89)
            street: 'Via Cornara 24',
            postalCode: '30030',
            city: 'Pianiga',
            province: 'VE',
            country: 'Italia',
            email: 'Delucaernesto9@gmail.com',
            phone: '',
            logoPath: null,
        );
    }
}
