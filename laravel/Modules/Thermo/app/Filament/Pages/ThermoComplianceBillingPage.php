<?php

declare(strict_types=1);

namespace Modules\Thermo\Filament\Pages;

final class ThermoComplianceBillingPage extends ThermoPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'UI-06 - Documenti e fatture';

    protected static ?string $title = 'UI-06 - DiCo, foto finali, fatture e pagamenti';

    protected static ?string $slug = 'compliance-billing';

    protected static ?int $navigationSort = 6;

    protected static ?string $thermoFeature = 'thermo.compliance_billing';

    protected string $view = 'thermo::filament.pages.compliance-billing';
}
