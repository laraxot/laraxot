# Code quality — modulo Xot

Report locale (2026-07-17). Metodo: `phpstan analyse` livello max, `phpmd` (ruleset codesize+unusedcode), grep mirati (TODO/FIXME/@deprecated, dd()/dump(), facade in app/Actions, extends Filament diretto), rapporto file test/app.

## Numeri

- File in `app/`: 569
- File di test: 161 — rapporto test/app: 28%
- File con TODO/FIXME/@deprecated: 9
- PHPStan: 0 errori (livello max, sweep repo-wide 2026-07-16/17)
- Violazioni PHPMD (codesize+unusedcode): 262
- File in `app/Actions/` che importano Facade Laravel direttamente (violazione pattern QueueableAction, vedi skill `queueable-action-trait`): 57

### File con Facade in Actions da convertire

- Modules/Xot/app/Actions/ArtisanAction.php
- Modules/Xot/app/Actions/ThemeAction.php
- Modules/Xot/app/Actions/ModuleAction.php
- Modules/Xot/app/Actions/RouteDynAction.php
- Modules/Xot/app/Actions/ExecuteArtisanCommandAction.php
- Modules/Xot/app/Actions/HtmlAction.php
- Modules/Xot/app/Actions/GetViewByClassAction.php
- Modules/Xot/app/Actions/Module/GetModuleConfigAction.php
- Modules/Xot/app/Actions/Module/GetModulePathByGeneratorAction.php
- Modules/Xot/app/Actions/Dummy/GetProductsArrayDummyAction.php
- Modules/Xot/app/Actions/File/GetComponentsAction.php
- Modules/Xot/app/Actions/File/CreateDirectoryForFilenameAction.php
- Modules/Xot/app/Actions/File/CopyAction.php
- Modules/Xot/app/Actions/File/FileAction.php
- Modules/Xot/app/Actions/File/GetModulePathAction.php
- Modules/Xot/app/Actions/File/DownloadZipByPathsDiskAction.php
- Modules/Xot/app/Actions/File/AddStrictTypesDeclarationAction.php
- Modules/Xot/app/Actions/File/GetViewNameSpacePathAction.php
- Modules/Xot/app/Actions/File/SvgExistsAction.php
- Modules/Xot/app/Actions/File/AssetAction.php
- Modules/Xot/app/Actions/Model/StoreAction.php
- Modules/Xot/app/Actions/Model/GetAllModelsByModuleNameAction.php
- Modules/Xot/app/Actions/Model/UpdateAction.php
- Modules/Xot/app/Actions/Model/TableExistsByModelClassActions.php
- Modules/Xot/app/Actions/Model/DestroyAction.php
- Modules/Xot/app/Actions/Model/HasColumnAction.php
- Modules/Xot/app/Actions/Model/Update/MorphOneAction.php
- Modules/Xot/app/Actions/Model/Update/BelongsToManyAction.php
- Modules/Xot/app/Actions/Model/Update/MorphToOneAction.php
- Modules/Xot/app/Actions/Model/Store/BelongsToManyAction.php
- Modules/Xot/app/Actions/Model/Store/MorphToOneAction.php
- Modules/Xot/app/Actions/Pdf/GetPdfContentByRecordAction.php
- Modules/Xot/app/Actions/Pdf/Engine/SpipuPdfByHtmlAction.php
- Modules/Xot/app/Actions/Pdf/Engine/SpatiePdfByHtmlAction.php
- Modules/Xot/app/Actions/Query/CreateTableIndexByModelClassColumnsAction.php
- Modules/Xot/app/Actions/Query/StartQueryLogAction.php
- Modules/Xot/app/Actions/Query/GetFieldnamesByTablenameAction.php
- Modules/Xot/app/Actions/Generate/GenerateModelByModelClass.php
- Modules/Xot/app/Actions/ModelClass/TableExistsByModelClassActions.php
- Modules/Xot/app/Actions/Factory/GetFactoryAction.php
- Modules/Xot/app/Actions/Panel/ApplyMetatagToPanelAction.php
- Modules/Xot/app/Actions/Html/HtmlToPdfAction.php
- Modules/Xot/app/Actions/Filament/RenderContextNavigation.php
- Modules/Xot/app/Actions/Filament/GenerateTableColumnsByFileAction.php
- Modules/Xot/app/Actions/Filament/GetPanelsNavigationItems.php
- Modules/Xot/app/Actions/Filament/GetModulesNavigationItems.php
- Modules/Xot/app/Actions/Filament/Block/GetViewBlocksOptionsByTypeAction.php
- Modules/Xot/app/Actions/Theme/GetThemeAction.php
- Modules/Xot/app/Actions/Theme/SetThemeAction.php
- Modules/Xot/app/Actions/View/GetViewsSiblingsAndSelfAction.php
- Modules/Xot/app/Actions/Export/PdfByHtmlAction_Portrait.php
- Modules/Xot/app/Actions/Export/PdfByHtmlAction.php
- Modules/Xot/app/Actions/Import/ImportCsvAction.php
- Modules/Xot/app/Actions/Blade/RegisterBladeComponentsAction.php
- Modules/Xot/app/Actions/Config/GetTenantConfigArrayAction.php
- Modules/Xot/app/Actions/AI/Ollama/GenerateOllamaAction.php
- Modules/Xot/app/Actions/AI/Ollama/ChatOllamaAction.php

### Complessità / dimensione classi da rivedere

- Modules/Xot/app/Actions/Arr/RangeIntersectAction.php:19                                           CyclomaticComplexity      The method execute() has a Cyclomatic Complexity of 13. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/ArtisanAction.php:38                                                      CyclomaticComplexity      The method act() has a Cyclomatic Complexity of 24. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/Cast/SafeArrayCastAction.php:48                                           CyclomaticComplexity      The method execute() has a Cyclomatic Complexity of 13. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/Cast/SafeFloatCastAction.php:46                                           CyclomaticComplexity      The method execute() has a Cyclomatic Complexity of 12. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/Cast/SafeIntCastAction.php:34                                             CyclomaticComplexity      The method execute() has a Cyclomatic Complexity of 12. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/ContextCompressor.php:73                                                  CyclomaticComplexity      The method extractCompressedText() has a Cyclomatic Complexity of 13. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/ContextCompressorAction.php:73                                            CyclomaticComplexity      The method extractCompressedText() has a Cyclomatic Complexity of 13. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/ExecuteArtisanCommandAction.php:53                                        CyclomaticComplexity      The method execute() has a Cyclomatic Complexity of 11. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/Export/ExportXlsStreamByLazyCollection.php:96                             CyclomaticComplexity      The method headings() has a Cyclomatic Complexity of 10. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/Factory/GetPropertiesFromMethodsByModelAction.php:41                      CyclomaticComplexity      The method execute() has a Cyclomatic Complexity of 10. The configured cyclomatic complexity threshold is 10.

## Stato architetturale

- Nessuna violazione `extends \Filament\...` diretto rilevata (regola XotBase rispettata).

## Azioni consigliate

- Triage dei 9 file con TODO/FIXME aperti.
- Convertire le 57 Action con Facade dirette al pattern QueueableAction (niente facade nella cartella Actions).
- Rifattorizzare i metodi/classi elencati sopra (complessità ciclomatica/NPath oltre soglia).

## Confronto con gli altri moduli (rapporto test/app)

| Modulo | app | test | % | facade-in-Actions |
|---|---|---|---|---|
| Activity | - | - | 127% | 5 |
| AI | - | - | 42% | 2 |
| Blog | - | - | 0% | 2 |
| Cms | - | - | 102% | 1 |
| Comment | - | - | 26% | 2 |
| Employee | - | - | 26% | 1 |
| Gdpr | - | - | 52% | 4 |
| Geo | - | - | 41% | 34 |
| Job | - | - | 21% | 3 |
| Lang | - | - | 30% | 3 |
| Media | - | - | 11% | 10 |
| Notify | - | - | 61% | 21 |
| Rating | - | - | 7% | 0 |
| Seo | - | - | 100% | 0 |
| TechPlanner | - | - | 2% | 0 |
| Tenant | - | - | 75% | 6 |
| UI | - | - | 34% | 4 |
| User | - | - | 23% | 4 |
| Xot | - | - | 28% | 57 |



## Come migliorare — modifiche effettive da fare

### 1. Rimuovere le Facade da `app/Actions/`

Regola del progetto (skill `queueable-action-trait`): nelle Action **niente Facade**, le dipendenze si iniettano nel costruttore — il container le risolve automaticamente quando l'Action viene chiamata con `app(XxxAction::class)->execute(...)`.

Facade usate in questo modulo e relativa dipendenza da iniettare al loro posto:

| Facade | Inietta invece |
|---|---|
| `App::` | `Illuminate\Contracts\Foundation\Application` |
| `Artisan::` | `Illuminate\Contracts\Console\Kernel` |
| `Auth::` | `Illuminate\Contracts\Auth\Factory` |
| `Cache::` | `Illuminate\Contracts\Cache\Repository` |
| `Config::` | `Illuminate\Contracts\Config\Repository` |
| `DB::` | `Illuminate\Database\ConnectionInterface` |
| `Event::` | `Illuminate\Contracts\Events\Dispatcher` |
| `File::` | `Illuminate\Filesystem\Filesystem` |
| `Http::` | `Illuminate\Http\Client\Factory` |
| `Log::` | `Psr\Log\LoggerInterface` |
| `Module::` | `Nwidart\Modules\Contracts\RepositoryInterface` |
| `Route::` | `Illuminate\Routing\UrlGenerator` |
| `Schema::` | `Illuminate\Database\ConnectionInterface (poi ->getSchemaBuilder())` |
| `Session::` | `Illuminate\Contracts\Session\Session` |
| `Storage::` | `Illuminate\Contracts\Filesystem\Factory` |
| `Validator::` | `Illuminate\Contracts\Validation\Factory` |
| `View::` | `Illuminate\Contracts\View\Factory` |

**Esempio concreto** — `Modules/Xot/app/Actions/Module/GetModuleConfigAction.php`:

```php
// PRIMA
use Illuminate\Support\Facades\Http;

class XxxAction
{
    use QueueableAction;

    public function execute(string $arg): mixed
    {
        $response = Http::get($url);
        // ...
    }
}

// DOPO
use Illuminate\Http\Client\Factory as HttpFactory;

class XxxAction
{
    use QueueableAction;

    public function __construct(private readonly HttpFactory $http)
    {
    }

    public function execute(string $arg): mixed
    {
        $response = $this->http->get($url);
        // ...
    }
}
```

Vantaggio pratico: l'Action diventa testabile senza `Http::fake()` globale — nei test Pest si passa un mock/fake del client via `app()->instance(HttpFactory::class, $fakeClient)` o via binding nel service provider di test.

File da convertire in questo modulo (elenco sopra in "Numeri"), uno alla volta, con `php -l` + PHPStan L max sul singolo file dopo ogni modifica.

### 2. Ridurre la complessità ciclomatica

Metodi/classi oltre soglia (10 per metodo, 50 per classe) in questo modulo:

- Modules/Xot/app/Actions/Arr/RangeIntersectAction.php:19                                           CyclomaticComplexity      The method execute() has a Cyclomatic Complexity of 13. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/ArtisanAction.php:38                                                      CyclomaticComplexity      The method act() has a Cyclomatic Complexity of 24. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/Cast/SafeArrayCastAction.php:48                                           CyclomaticComplexity      The method execute() has a Cyclomatic Complexity of 13. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/Cast/SafeFloatCastAction.php:46                                           CyclomaticComplexity      The method execute() has a Cyclomatic Complexity of 12. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/Cast/SafeIntCastAction.php:34                                             CyclomaticComplexity      The method execute() has a Cyclomatic Complexity of 12. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/ContextCompressor.php:73                                                  CyclomaticComplexity      The method extractCompressedText() has a Cyclomatic Complexity of 13. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/ContextCompressorAction.php:73                                            CyclomaticComplexity      The method extractCompressedText() has a Cyclomatic Complexity of 13. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/ExecuteArtisanCommandAction.php:53                                        CyclomaticComplexity      The method execute() has a Cyclomatic Complexity of 11. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/Export/ExportXlsStreamByLazyCollection.php:96                             CyclomaticComplexity      The method headings() has a Cyclomatic Complexity of 10. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/Factory/GetPropertiesFromMethodsByModelAction.php:41                      CyclomaticComplexity      The method execute() has a Cyclomatic Complexity of 10. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/File/FileAction.php:30                                                    ExcessiveClassComplexity  The class FileAction has an overall complexity of 129 which is very high. The configured complexity threshold is 50.
- Modules/Xot/app/Actions/File/FileAction.php:58                                                    CyclomaticComplexity      The method asset() has a Cyclomatic Complexity of 19. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/File/FileAction.php:435                                                   CyclomaticComplexity      The method viewNamespaceToAsset() has a Cyclomatic Complexity of 11. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/File/FileAction.php:933                                                   CyclomaticComplexity      The method getComponents() has a Cyclomatic Complexity of 10. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/File/GetComponentsAction.php:27                                           CyclomaticComplexity      The method execute() has a Cyclomatic Complexity of 14. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/File/GetViewNameSpacePathAction.php:22                                    CyclomaticComplexity      The method execute() has a Cyclomatic Complexity of 11. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/GetTransKeyAction.php:20                                                  CyclomaticComplexity      The method execute() has a Cyclomatic Complexity of 12. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/Mail/SendMailByRecordAction.php:24                                        CyclomaticComplexity      The method execute() has a Cyclomatic Complexity of 11. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/Model/Update/BelongsToAction.php:20                                       CyclomaticComplexity      The method execute() has a Cyclomatic Complexity of 11. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/Pdf/MakePdfSpatieTestAction.php:46                                        CyclomaticComplexity      The method makePdfBuilder() has a Cyclomatic Complexity of 13. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Actions/Theme/GetThemeContextAction.php:28                                        CyclomaticComplexity      The method execute() has a Cyclomatic Complexity of 13. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Console/Commands/AddStrictTypesDeclarationCommand.php:32                          CyclomaticComplexity      The method handle() has a Cyclomatic Complexity of 11. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Console/Commands/OptimizeFilamentMemoryCommand.php:21                             ExcessiveClassComplexity  The class OptimizeFilamentMemoryCommand has an overall complexity of 60 which is very high. The configured complexity threshold is 50.
- Modules/Xot/app/Console/Commands/SearchTextInDbCommand.php:19                                     CyclomaticComplexity      The method handle() has a Cyclomatic Complexity of 19. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Database/Migrations/XotBaseMigration.php:28                                       ExcessiveClassComplexity  The class XotBaseMigration has an overall complexity of 96 which is very high. The configured complexity threshold is 50.
- Modules/Xot/app/Database/Migrations/XotBaseMigration.php:311                                      CyclomaticComplexity      The method updateTimestamps() has a Cyclomatic Complexity of 10. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Datas/MetatagData.php:62                                                          ExcessiveClassComplexity  The class MetatagData has an overall complexity of 68 which is very high. The configured complexity threshold is 50.
- Modules/Xot/app/Datas/XotData.php:28                                                              ExcessiveClassComplexity  The class XotData has an overall complexity of 58 which is very high. The configured complexity threshold is 50.
- Modules/Xot/app/Filament/Actions/Header/ExportXlsLazyAction.php:22                                CyclomaticComplexity      The method setUp() has a Cyclomatic Complexity of 10. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Filament/Builders/FilterBuilder.php:87                                            CyclomaticComplexity      The method dateRange() has a Cyclomatic Complexity of 13. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Filament/Resources/RelationManagers/XotBaseRelationManager.php:116                CyclomaticComplexity      The method getTableColumns() has a Cyclomatic Complexity of 16. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Helpers/ResourceFormSchemaGenerator.php:72                                        CyclomaticComplexity      The method generateForAllResources() has a Cyclomatic Complexity of 10. The configured cyclomatic complexity threshold is 10.
- Modules/Xot/app/Http/Middleware/SecurityMiddleware.php:22                                         ExcessiveClassComplexity  The class SecurityMiddleware has an overall complexity of 50 which is very high. The configured complexity threshold is 50.
- Modules/Xot/app/Models/Traits/HasExtraTrait.php:54                                                CyclomaticComplexity      The method getExtra() has a Cyclomatic Complexity of 11. The configured cyclomatic complexity threshold is 10.

Tecnica di refactoring consigliata: **estrarre ogni ramo condizionale in un metodo privato dedicato**, o sostituire lunghe catene if/elseif con una `match()` che delega a metodi/Action più piccoli. Esempio:

```php
// PRIMA — un metodo con 15+ rami
public function resolveType(string $type): string
{
    if ($type === "a") { /* ... */ }
    elseif ($type === "b") { /* ... */ }
    // ... altri 10+ rami
}

// DOPO — dispatch table, ogni ramo è un metodo testabile singolarmente
public function resolveType(string $type): string
{
    return match ($type) {
        "a" => $this->resolveA(),
        "b" => $this->resolveB(),
        default => throw new \InvalidArgumentException("Unknown type: {$type}"),
    };
}
```

Ogni `resolveX()` estratto scende sotto soglia 10 e diventa testabile in isolamento con un test Pest dedicato.

