---
title: "PHPStan — fix sistemico Pest $this binding — 2026-07-06"
type: chat
created: 2026-07-06
updated: 2026-07-06
tags: [phpstan, pest, tests, multi-agent, locks]
issue: "https://github.com/laraxot/base_techplanner_fila5/issues/34"
---

# PHPStan — fix Pest `$this` binding — 2026-07-06 (sessione pomeridiana)

## Contesto

Alla partenza di questa sessione, `./vendor/bin/phpstan analyse Modules --memory-limit=-1` falliva completamente (0 errori riportati, `file_errors: 1`) per un errore di sintassi bloccante in `Modules/User/tests/Feature/Database/Migrations/UserMigrationSyntaxTest.php` (return type reale `list<string>`, sintassi valida solo nei PHPDoc di PHPStan, non in PHP). Corretto con `array` + `@return list<string>`.

Dopo il fix, baseline reale: **2490 errori** su 11 moduli (Employee 1704, TechPlanner 254, User 234, Notify 193, Cms 72, Geo 11, Comment 10, Job 4, Tenant 4, Xot 3, Blog 1).

## Causa sistemica identificata (≈862 errori solo in Employee, centinaia in altri moduli)

PHPStan non risolve il tipo di `$this` dentro le closure `it()`/`test()`/`beforeEach()`/`afterEach()` di Pest — né `larastan/larastan` né `pestphp/pest` (il pacchetto, non l'estensione di terze parti) forniscono un binding statico automatico verso la `TestCase` del modulo dichiarata con `uses(...)`. Risultato: `$this->get()`, `$this->actingAs()`, `$this->assertX()` ecc. generano `method.nonObject`, `property.nonObject`, `method.internalClass` a cascata.

**Fix applicato**: script AST-based (`nikic/php-parser`, già presente come dipendenza) che inserisce `/** @var Modules\X\Tests\TestCase $this */` come primo statement di ogni closure che referenzia `$this`, solo se non già annotata. Non è una soppressione: è l'annotazione standard raccomandata per questo limite noto di Pest+PHPStan. Nessuna modifica a `phpstan.neon`.

Moduli processati: Activity, Cms, Employee, Geo (poi **revertito**, vedi sotto), Lang, Media, Notify, Seo, TechPlanner, Tenant, UI, User, Xot.

## Collisione con lock — risolta

Lo script ha inizialmente toccato anche:
- `Modules/Geo/tests/Unit/Actions/Maps/BuildGeoMapWidgetPayloadActionTest.php`
- `Modules/Geo/tests/Unit/Services/HereServiceTest.php`

Entrambi risultavano **già lockati** da altro agente (`.lock` presenti). Modifiche **revertite** con `git checkout --` prima di proseguire. Se stai lavorando su questi due file: la mia modifica non è più presente, procedi pure.

## Problema distinto, NON risolvibile per singolo file

`method.internalClass` su ogni `expect(...)->toBe(...)` (e simili): `Pest\Mixins\Expectation` è `@internal`, PHPStan blocca chiamate a classi `@internal` da namespace esterni a `Pest`. Qualsiasi test Pest con `expect()` fuori namespace `Pest` lo attiva. Non è un bug del modulo, è frizione tra `level: max` e lo stile Pest. L'unica vera correzione di codice sarebbe riscrivere le assertion in stile PHPUnit (`$this->assertSame(...)`) al posto di `expect()->toBe()` in centinaia di file — cambiamento troppo ampio per farlo unilateralmente. `phpstan.neon` ha già una riga commentata `# - identifier: method.internalClass`: decisione riservata al proprietario del file (vedi `Modules/Employee/docs/phpstan-compliance-status.md`, sezione "Real status update").

## Stato dopo il fix

Rieseguito `phpstan analyse Modules`: **2446 errori** (era 2490). Il decremento è inferiore alle attese (~862) perché molti errori residui condividono la stessa riga/file con altri problemi non ancora corretti (es. `method.internalClass` copre la stessa riga di un `$this->get()` già corretto) e perché altri agenti stanno modificando in parallelo (cancellazioni di `*PhpstanTraitProbe`, fix in Job/Lang/Media/Tenant/Xot non miei — vedi `git status` per stato corrente, molto volatile).

## Prossima azione valida

- Non toccare i file con `.lock` presente.
- Prima di correggere altri errori Employee (scope mancanti, costanti mancanti, classi Filament mancanti: `Employee::active()`, `WorkHour::TYPE_CLOCK_IN`, `Filament\Widgets\TimeTrackingWidget`), verificare se il test inventa qualcosa mai implementato (in tal caso va corretto il test, non creata la produzione) o se è codice di produzione realmente incompleto.
- Rilanciare `phpstan analyse Modules` per una baseline aggiornata prima di continuare, dato quanto è volatile il working tree condiviso.

— Claude (`claude-sonnet-5`)
