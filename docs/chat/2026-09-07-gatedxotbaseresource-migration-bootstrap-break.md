# GatedXotBaseResource → XotBaseResource: bootstrap rotto (Platform), non solo Billing

**Stato**: RISOLTO — verificato 2026-09-07 ~22:05 (claude sonnet 5). Sia
`AuditLogResource.php` che `PlatformSettingResource.php` hanno ora
`use Modules\Xot\Filament\Resources\XotBaseResource;`. `phpstan analyse
Modules/Platform` (cache pulita): 0 errori, nessun bootstrap fatal. Fix
arrivato da un altro agente/sessione durante la giornata (probabilmente lo
stesso passaggio Epic4 canonical-structure osservato in tempo reale su
`2026-09-07-phpstan-platform-standdown-live-edit.md`), non da questa nota.
**Confermato il rischio architetturale sotto**: `InvoiceResource` (Billing)
ora estende `XotBaseResource` direttamente (commit `90af443`, story 4.1.1),
senza `use GatesResourceNavigation;` — a differenza di
EnergyBroker/Compliance(x4)/PublicProcurement(x3) che l'hanno aggiunto
correttamente nella stessa migrazione. `NavigationGate::isItemEnabled()`
non ha piu' alcun effetto su `InvoiceResource` nel pannello cliente
(`CustomerPanelProvider`).

**Trappola tecnica se si aggiunge il trait al volo**: `InvoiceResource`
dichiara GIA' i suoi `shouldRegisterNavigation()`/`canViewAny()` custom
(logica `isAvailableInCurrentPanel()` + `AccountFeatures`). Un metodo di
classe sovrascrive SEMPRE il metodo omonimo di un trait in PHP, senza
errori: aggiungere `use GatesResourceNavigation;` così com'è non farebbe
NULLA (i due metodi della classe restano attivi, quelli del trait
diventano codice morto silenzioso) — non è un fix "aggiungi il trait e
via", serve AND-are esplicitamente `NavigationGate::isItemEnabled(static::class)`
dentro i due metodi custom esistenti.

**RISOLTO 2026-09-07 ~22:10**: utente ha confermato di procedere. Fix
applicato — `NavigationGate::isItemEnabled(static::class)` AND-ato dentro
`shouldRegisterNavigation()`/`canViewAny()` esistenti (non aggiunto il
trait, sarebbe stato shadowato). `phpstan analyse Modules/Billing`: 0
errori. Commit `0cff9f9` (module_billing_fila5) + `35d43bdd4` (root),
entrambi pushati.

## Cosa ho trovato

Mentre lavoravo su `4.1.filament-resource-gap-billing.story.md` (estrazione Schemas/Tables
per Supplier/SupplierInvoice), un run PHPStan su `Modules/Billing` e' fallito in bootstrap
con:

```
Application bootstrap failed
Error: Class "Modules\Platform\Filament\Resources\XotBaseResource" not found
```

Root cause (`git -C Modules/Platform blame`, non committato, 2026-09-07 14:40):

- `Modules/Platform/app/Filament/Resources/AuditLogResource.php`
- `Modules/Platform/app/Filament/Resources/PlatformSettingResource.php`

Entrambi cambiati da `extends GatedXotBaseResource` a `extends XotBaseResource` (rename
grezzo, probabilmente in risposta alla stessa richiesta utente "non usare
GatedXotBaseResource, usa XotBaseResource direttamente" che ho ricevuto anch'io su
Billing) **senza aggiungere** `use Modules\Xot\Filament\Resources\XotBaseResource;`.
Risultato: PHP risolve `XotBaseResource` nel namespace corrente
(`Modules\Platform\Filament\Resources`), che non esiste → il Panel non bootstrappa →
**PHPStan si rompe su tutto l'albero `Modules`, non solo Platform**, perche' la
registrazione dei panel Filament carica tutte le Resource.

Entrambi i file sono lockati (`*.lock`, non stale) da un altro agente: non li ho toccati.
Se leggi questo file e tieni tu quel lock, l'unico fix e' aggiungere l'import mancante:

```php
use Modules\Xot\Filament\Resources\XotBaseResource;
```

## Sulla direttiva "non usare GatedXotBaseResource"

Prima di rimuoverlo ovunque: `GatedXotBaseResource` non e' solo stile, fa un lavoro reale
(`Modules/Platform/app/Filament/Concerns/GatesResourceNavigation.php`) — nasconde la voce
di navigazione e blocca l'URL diretto in base a `NavigationGate::isItemEnabled()` (runtime
feature toggle). `Modules/Platform/app/Providers/Filament/CustomerPanelProvider.php`
registra `Billing\InvoiceResource` in quel panel: se `InvoiceResource` passa a
`XotBaseResource` diretto, quel toggle smette di avere effetto per gli utenti del panel
cliente. Per `SupplierResource`/`SupplierInvoiceResource`/`DeliveryNoteResource` (solo
pannello operatore, nessun riferimento trovato in `CustomerPanelProvider`) il downgrade
sembra innocuo — per `InvoiceResource` va verificato con chi ha dato la direttiva prima di
applicarlo, non assunto.

## Bug indipendente trovato nello stesso lavoro (Billing)

`SuppliersTable.php` (gia' corretto da me) e la versione iniziale di
`SupplierInvoicesTable.php` non definivano `getTableActions()`: l'hook di default di
`HasXotTable` popola Vedi/Modifica solo se il Resource espone `canView()`/`canEdit()`
(nessuna Resource Billing li definisce), quindi senza override la tabella perde i bottoni
riga. Verificare la stessa cosa su `DeliveryNotesTable.php` (Billing, altro agente/lock)
prima di chiudere la story 4.1 — non l'ho corretto io perche' il file e' fuori dal mio lock.

## Riferimenti

- `laravel/Modules/Billing/docs/stories/4.1.filament-resource-gap-billing.story.md`
- `bashscripts/docs/prompts/06-filament-audit.md` (v3.0.0)
- `laravel/Modules/Platform/app/Filament/Concerns/GatesResourceNavigation.php`
- `laravel/Modules/Platform/app/Providers/Filament/CustomerPanelProvider.php`
