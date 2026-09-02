<?php

declare(strict_types=1);

use Modules\Xot\Tests\TestCase;

use function Safe\file_get_contents;
use function Safe\glob;

uses(TestCase::class);

/**
 * Una pagina che estende XotBaseListRecords non eredita le colonne dal `table()`
 * della Resource: HasXotTable::table() ricostruisce la tabella da zero chiamando
 * `getTableColumns()`. Se la pagina non lo dichiara e la Resource non ha una
 * classe `Tables\<Model>sTable`, la tabella esce senza colonne — una pagina
 * vuota in faccia all'utente, che nessun test di dominio nota.
 *
 * Le pagine elencate in DEBITO sono gia' in questo stato da prima: sono
 * censite in docs/list-pages-without-columns.md e vanno svuotate da li',
 * non aggiunte.
 */
/** @var list<string> Pagine gia' rotte prima di questo test. Solo da accorciare. */
const DEBITO = [
    'Modules/Catalog/app/Filament/Resources/CatalogFrequencyResource/Pages/ListCatalogFrequencies.php',
    'Modules/Catalog/app/Filament/Resources/CatalogMeasureUnitResource/Pages/ListCatalogMeasureUnits.php',
    'Modules/Catalog/app/Filament/Resources/CatalogModelResource/Pages/ListCatalogModels.php',
    'Modules/Catalog/app/Filament/Resources/CatalogPartTypeResource/Pages/ListCatalogPartTypes.php',
    'Modules/Catalog/app/Filament/Resources/MetelCatalogResource/Pages/ListMetelCatalogs.php',
    'Modules/Catalog/app/Filament/Resources/ProductResource/Pages/ListProducts.php',
    'Modules/Customer/app/Filament/Resources/CustomerResource/Pages/ListCustomers.php',
    'Modules/Customer/app/Filament/Resources/ResellerResource/Pages/ListResellers.php',
    'Modules/Geo/app/Filament/Resources/Pages/ListLocations.php',
    'Modules/HR/app/Filament/Resources/AbsenceRequestResource/Pages/ListAbsenceRequests.php',
    'Modules/HR/app/Filament/Resources/EmployeeResource/Pages/ListEmployees.php',
    'Modules/Intervention/app/Filament/Resources/DailyNoteResource/Pages/ListDailyNotes.php',
    'Modules/Intervention/app/Filament/Resources/InterventionResource/Pages/ListInterventions.php',
    'Modules/Lang/app/Filament/Resources/Pages/LangBaseListRecords.php',
    'Modules/UI/app/Filament/Resources/Pages/BaseListRecords.php',
    'Modules/WorkOrder/app/Filament/Resources/WorkOrderResource/Pages/ListWorkOrders.php',
];

it('every list page declares its table columns', function (): void {
    $root = base_path();
    $pages = [];

    foreach (glob($root.'/Modules/*/app/Filament', GLOB_ONLYDIR) as $dir) {
        if (! is_string($dir)) {
            continue;
        }

        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach ($it as $file) {
            if (! $file instanceof SplFileInfo || $file->getExtension() !== 'php') {
                continue;
            }

            $path = $file->getPathname();
            $source = file_get_contents($path);
            if (! str_contains($source, 'extends XotBaseListRecords')) {
                continue;
            }

            $relative = str_replace($root.'/', '', $path);
            if (in_array($relative, DEBITO, true)) {
                continue;
            }

            // La pagina va bene se dichiara le colonne, oppure se la Resource
            // che la ospita ha la classe Tables/ che XotBaseResource cerca.
            $resourceDir = dirname($path, 2);
            if (str_contains($source, 'function getTableColumns') || is_dir($resourceDir.'/Tables')) {
                continue;
            }

            $pages[] = $relative;
        }
    }

    sort($pages);

    expect($pages)->toBe([], "Queste pagine renderebbero una tabella senza colonne:\n  ".implode("\n  ", $pages));
});
