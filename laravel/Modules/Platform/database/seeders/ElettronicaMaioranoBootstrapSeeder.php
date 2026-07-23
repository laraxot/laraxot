<?php

declare(strict_types=1);

namespace Modules\Platform\Database\Seeders;

use Modules\Platform\ValueObjects\CompanyProfile;

/**
 * Bootstrap istanza per ELETTRONICA MAIORANO S.R.L.S. (Olevano sul Tusciano, SA) —
 * installazione e manutenzione impianti elettrici, societa di capitali (regime ordinario).
 * Password titolare da env MAIORANO_ADMIN_PASSWORD.
 */
final class ElettronicaMaioranoBootstrapSeeder extends ThermoInstanceBootstrapSeeder
{
    protected function ownerEmail(): string
    {
        // TODO: sostituire con l'email reale del titolare (usata come login del pannello).
        return 'elettronica.maiorano@gmail.com';
    }

    protected function ownerName(): string
    {
        return 'Elettronica Maiorano';
    }

    protected function passwordEnvKey(): string
    {
        return 'MAIORANO_ADMIN_PASSWORD';
    }

    protected function companyProfile(): CompanyProfile
    {
        return new CompanyProfile(
            legalName: 'Elettronica Maiorano S.r.l.s.',
            vatNumber: 'IT05446000654',
            taxCode: '05446000654', // per le societa il C.F. coincide con la P.IVA
            taxRegime: 'RF01', // ordinario (societa di capitali)
            street: 'Via Frosano 189',
            postalCode: '84062',
            city: 'Olevano sul Tusciano',
            province: 'SA',
            country: 'Italia',
            email: '', // email/PEC non disponibile online: da completare
            phone: '', // telefono non disponibile online: da completare
            logoPath: null,
        );
    }
}
