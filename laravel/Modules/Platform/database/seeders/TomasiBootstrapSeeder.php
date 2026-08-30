<?php

declare(strict_types=1);

namespace Modules\Platform\Database\Seeders;

use Modules\Platform\ValueObjects\CompanyProfile;

/**
 * Bootstrap istanza per TOMASI ELENA — termoidraulica, ditta individuale.
 * Password titolare da env TOMASI_ADMIN_PASSWORD.
 */
final class TomasiBootstrapSeeder extends ThermoInstanceBootstrapSeeder
{
    protected function ownerEmail(): string
    {
        return 'elena.tomasi@yahoo.it';
    }

    protected function ownerName(): string
    {
        return 'Elena Tomasi';
    }

    protected function passwordEnvKey(): string
    {
        return 'TOMASI_ADMIN_PASSWORD';
    }

    protected function companyProfile(): CompanyProfile
    {
        return new CompanyProfile(
            legalName: 'Tomasi Elena', // ragione sociale da confermare
            vatNumber: '', // P.IVA non disponibile: da completare
            taxCode: '', // C.F. personale non disponibile: da completare
            taxRegime: 'RF19', // forfettario (L. 190/2014, art. 1 c. 54-89): da confermare
            street: '', // sede legale non disponibile: da completare
            postalCode: '',
            city: '',
            province: '',
            country: 'Italia',
            email: 'elena.tomasi@yahoo.it',
            phone: '',
            logoPath: null,
        );
    }
}
