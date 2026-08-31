# Import status — Wts

Modulo **nuovo** della seconda tranche. Sorgente: `gestionale_commesse/app/Wts`,
`app/Filament/Wts` e `app/Providers/Filament/WtsPanelProvider`, cioe' codice che nel
progetto di origine viveva in `app/` e non era mai stato portato.

## Riorganizzazione

| Sorgente | Destinazione |
|---|---|
| `app/Wts/**` (Actions, Datas, Queries, Support, Models) | `app/**` con namespace `Modules\Wts\*` |
| `app/Wts/Ecos/EcosOdbcConnection` | `app/Ecos/EcosOdbcConnection` |
| `app/Filament/Wts/Pages/*` | `app/Filament/Pages/*` |
| `app/Providers/Filament/WtsPanelProvider` | `app/Providers/Filament/AdminPanelProvider` (`XotBasePanelProvider`, `panelId`/`panelPath` = `wts`) |
| `lib/mojito-label/src` (`ZplBuilder`, `TypeCaster`) | `app/Label` |
| migrazioni sparse | 12 `XotBaseMigration`, una per tabella |
| viste `resources/views/filament/wts` | `resources/views` con hint `wts::` |

## Adattamenti

- **Migrazioni**: le colonne aggiunte nel tempo da migrazioni successive sono state
  ripiegate dentro la `create_*` della rispettiva tabella, come vuole la convenzione.
  I 9 modelli con `SoftDeletes` hanno `updateTimestamps($table, hasSoftDeletes: true)`.
- **`WtsProcessingStepUnit`** e `BasePivot` sono stati creati apposta: `XotBaseMigration`
  ricava il nome tabella dal modello, quindi anche la pivot ne vuole uno.
- **Identificativi utente**: in questa base l'id utente e' un UUID, non un intero. Le
  action che accettavano `?int $userId` ora accettano `int|string|null`; un cast a `int`
  avrebbe troncato l'UUID a `0` e rotto silenziosamente ownership e audit trail.
- **Gateway scheda tecnica**: `LegacyTechnicalSheetGateway` viene legato
  a `ConfiguredLegacyTechnicalSheetGateway` nel service provider del modulo.
- **Config**: `config/wts.php` non usa `env()` (Larastan lo vieta fuori dalla config di
  root); i valori sono default espliciti, sovrascrivibili dalla config applicativa.
- **Namespace nelle viste**: le blade referenziavano `App\Filament\Wts\Pages\*` e
  `App\Wts\Support\*`; riscritte su `Modules\Wts\*`.
- **Colonne autore**: le 6 colonne `*_by_user_id` erano `foreignId` verso `users.id`.
  In questa base quell'id e' un UUID di 36 caratteri, quindi sono diventate
  `string(36)`; i guard `is_int($authId)` che scartavano l'UUID sono stati estesi.
- **Route**: `api/wts/print-jobs*` (agente di stampa) e `wts/qr/{qrCode}` erano nelle
  route dell'applicazione sorgente; ora stanno in `routes/api.php` e `routes/web.php`
  del modulo, col controller in `app/Http/Controllers`.
- **Logo etichetta**: `resources/wts/label-logo.gfa` e' un asset del modulo, letto con
  `module_path()` invece di `resource_path()`: il modulo si porta dietro cio' che stampa.
- **Gestione utenti nel pannello**: nel sorgente il filtro "solo utenti WTS" viveva
  dentro l'unica `UserResource` dell'app. Qui `Modules\User` e' condiviso e non deve
  conoscere il WTS, quindi il filtro sta in `Modules\Wts\Filament\Resources\UserResource`
  (estende quella di User) con le sue quattro Pages; il pannello monta quella.
  L'accesso e' del solo `wts_admin` e resta soggetto al toggle di "Moduli & Menu"
  (`NavigationGate`), non alla policy Shield: quella richiede permessi che nel
  pannello di impianto non ha nessuno e negherebbe la pagina anche a chi di dovere.
  La lista aggiunge l'azione "crea" all'export ereditato.

## Test allineati al contesto di destinazione

- `WtsOperationalPagesTest` non attende piu' il riquadro "documenti legali demo":
  linkava `route('terms')` e `route('privacy')` del sito vetrina sorgente, che qui
  non esistono. Il blocco e' stato tolto dalla dashboard.
- `WtsUserManagementTest` punta alla `UserResource` del modulo Wts (vedi sopra).

## Rinunce esplicite

- `ZplBuilder::renderImage()` non e' supportato. La libreria di origine delegava la
  conversione raster→ZPL a uno `ZplImageConverter` **mai scritto**: il ramo esplodeva a
  runtime. Le etichette WTS disegnano il QR con `^BQ` nativo, quindi il metodo rifiuta
  l'elemento invece di fingere un supporto che non c'e'.

## Delta `Modules/Auth` assorbito

Il delta della seconda tranche su `Modules/Auth` (commit `cd1cf2f`, `db4a599`,
`44f3eab`) era interamente logica del pannello WTS: lista utenti ristretta,
gating per `wts_admin`, tasto "crea" nella lista. Non esiste un modulo `Auth` in
questa base, quindi quel comportamento e' stato realizzato dove appartiene: nella
`UserResource` di questo modulo.

## Dipendenze aggiunte

- `bacon/bacon-qr-code` (QR SVG per le etichette).

## Effetti collaterali fuori dal modulo

- Rimosso da `Modules/Intervention/app/Stubs/LaravelCashier.php` lo stub di
  `Barryvdh\DomPDF\PDF`: dompdf ora e' installato davvero e lo stub, dichiarando la
  stessa classe con `setPaper(string)`, nascondeva l'API reale all'analisi statica.

## Stato

- PHPStan su `Modules/Wts/app`: **0 errori**.
- Pest: **205 test verdi** (800 asserzioni).
- Testsuite `Wts` registrata in `phpunit.xml`.
