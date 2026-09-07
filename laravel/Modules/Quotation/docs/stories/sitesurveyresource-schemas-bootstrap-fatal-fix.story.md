---
id: sitesurveyresource-schemas-bootstrap-fatal-fix
slug: sitesurveyresource-schemas-bootstrap-fatal-fix
title: "Quotation: SiteSurveyResource — 3 bug fatali nell'estrazione Schemas/Tables, bootstrap rotto repo-wide"
description: "SiteSurveyResource aveva Schemas/SiteSurveyForm.php e Schemas/SiteSurveyInfolist.php con `use` che punta a un namespace inesistente (segmento Resources/Schemas mancante) e getFormSchema()/getInfolistSchema() dichiarati come metodi di istanza invece che static, in violazione del contratto del genitore (static, a volte abstract): fatal error PHP a class-load time, non un warning. Tables/SiteSurveysTable.php ridefiniva table() (vietato) con action in un namespace Filament inesistente. Il fatal si propaga a tutto l'albero Modules perché Filament carica tutte le Resource in fase di bootstrap panel."
document_type: bmad_story
category: filament
status: done
priority: P0
owner: Quotation
created: 2026-09-07
updated_at: 2026-09-07
project: base_workorder_fila5
domain: filament-audit
source_of_truth: false
issues: []
discussions: []
tags:
  - bmad
  - filament
  - laraxot
  - quotation
  - xotbase
  - bootstrap
  - regression
  - p0
depends_on:
  - bashscripts/docs/prompts/06-filament-audit.md
  - laravel/Modules/Billing/docs/stories/4.1.filament-resource-gap-billing.story.md
  - laravel/Modules/Platform/docs/stories/4.2.gatedxotbaseresource-migration-bootstrap-fix.story.md
related:
  - laravel/Modules/Quotation/app/Filament/Resources/SiteSurveyResource.php
  - laravel/Modules/Quotation/app/Filament/Resources/SiteSurveyResource/Schemas/SiteSurveyForm.php
  - laravel/Modules/Quotation/app/Filament/Resources/SiteSurveyResource/Schemas/SiteSurveyInfolist.php
  - laravel/Modules/Quotation/app/Filament/Resources/SiteSurveyResource/Tables/SiteSurveysTable.php
  - laravel/Modules/Quotation/app/Filament/Resources/SiteSurveyResource/Pages/ViewSiteSurvey.php
  - laravel/Modules/Xot/app/Filament/Resources/Schemas/XotBaseResourceForm.php
  - laravel/Modules/Xot/app/Filament/Resources/Schemas/XotBaseResourceInfolist.php
  - laravel/Modules/Xot/app/Filament/Resources/Pages/XotBaseViewRecord.php
---

# Story — Quotation: SiteSurveyResource, 3 bug fatali post-estrazione

## Understand

### Problem (chiesto dall'utente: "cosa manca, perché manca")

`SiteSurveyResource` era a metà di un'estrazione verso la struttura canonica
(`Schemas/`+`Tables/`, story epic 4, swarm "opencode" già attivo su 14 moduli — vedi
second brain `project_epic4_filament_canonical_structure_swarm_active`). L'estrazione
in corso conteneva 3 classi di bug **fatali**, non solo gap strutturali:

1. **`use` con namespace sbagliato** (segmento mancante):
   - `Schemas/SiteSurveyForm.php`: `use Modules\Xot\Filament\Resources\XotBaseResourceForm;`
     invece di `Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm` (manca `Schemas\`).
   - `Schemas/SiteSurveyInfolist.php`: `use Modules\Xot\Filament\Schemas\XotBaseResourceInfolist;`
     invece di `Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist` (manca `Resources\`).
   - `Tables/SiteSurveysTable.php`: `use Modules\Xot\Filament\Tables\XotBaseResourceTable;`
     invece di `Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable` (manca `Resources\`).
   - Effetto: `class_exists()` (chiamato da `XotBaseResource::form()`/`table()` per
     l'auto-discovery) tenta l'autoload, che fallisce con `Class ... not found` —
     **PHP Fatal error**, non un errore PHPStan silenzioso.

2. **Override non-static di un metodo static del genitore**:
   - `getFormSchema()` e `getInfolistSchema()` erano dichiarati `public function` (istanza)
     mentre `XotBaseResourceForm`/`XotBaseResourceInfolist` li dichiarano
     `public static function` (il secondo persino `abstract`).
   - Verificato empiricamente (script PHP isolato): questo produce
     `PHP Fatal error: Cannot make static method X::getFormSchema() non static in
     class Y` — un errore di **dichiarazione di classe**, non di chiamata: la classe
     non è nemmeno caricabile.

3. **Override vietato di `table()` con namespace Action inesistente**:
   - `Tables/SiteSurveysTable.php` ridefiniva `public function table(Table $table): Table`
     (vietato — `HasXotTable.php:220` lo fornisce già, l'override rompe la
     composizione via hook `getTableColumns()`/`getTableFilters()`/ecc., anche se il
     commento nello stesso file diceva correttamente "NON ridefinisce table()").
   - Usava `Filament\Tables\Actions\ViewAction` ecc. — namespace **inesistente** in
     questa versione di Filament (verificato: `vendor/filament/tables/src/Actions/`
     contiene solo file di supporto, zero classi Action concrete; le Action vere
     vivono in `Filament\Actions\*`, pacchetto `filament/actions`).

4. **Bug indipendente, stessa Resource — Page-level infolist**:
   - `Pages/ViewSiteSurvey.php extends XotBaseViewRecord` non implementava il metodo
     `abstract protected function getInfolistSchema(): array` richiesto da
     `XotBaseViewRecord` — **un contratto completamente separato** da
     `Schemas/SiteSurveyInfolist.php` (quello è a livello Resource/form-schema
     Filament, questo è a livello Page/instance, firma diversa: `protected`, non
     static). Le due cose condividono il nome del metodo ma non l'implementazione.
     Fatal: "Class ... contains 1 abstract method and must therefore be declared
     abstract...".

### Perché il fatal si propaga a TUTTO `Modules`

Filament registra tutte le Resource di tutti i panel all'avvio per costruire le
route. Un fatal error nel caricamento di UNA classe Resource (o delle sue
Schemas/Tables/Pages) blocca il bootstrap dell'intera applicazione — quindi anche
`phpstan analyse Modules` su un modulo completamente diverso falliva con
"Application bootstrap failed". Stessa dinamica già vista e documentata in
`Modules/Platform/docs/stories/4.2.gatedxotbaseresource-migration-bootstrap-fix.story.md`
(causa diversa, stesso meccanismo di propagazione).

## Fix applicato

1. `Schemas/SiteSurveyForm.php`: `use` corretto a
   `Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm`, `getFormSchema()` →
   `public static function`.
2. `Schemas/SiteSurveyInfolist.php`: `use` corretto a
   `Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist`,
   `getInfolistSchema()` → `public static function` (poi espansa con più campi da un
   altro agente concorrente, contenuto convergente e corretto).
3. `Tables/SiteSurveysTable.php`: riscritta senza override di `table()`, hook
   `getTableColumns()`/`getTableFilters()`/`getTableActions()`/`getTableBulkActions()`,
   import corretti da `Filament\Actions\*`.
4. `Pages/ViewSiteSurvey.php`: aggiunto `protected function getInfolistSchema(): array`
   che delega a `SiteSurveyInfolist::getInfolistSchema()` (DRY, single source per i
   campi — pattern non ancora standard nel resto del repo, vedi nota sotto).

Verificato: `phpstan analyse Modules/Quotation` → 0 errori. `phpstan analyse Modules`
(intero albero) → 23 errori residui, tutti `typeCoverage.constantTypeCoverage`
(bucket strutturale tracciato da story `2.8.phpstan-constant-type-coverage`, non bug
per-file) — **zero bug reali residui repo-wide** al momento della verifica.

## Audit repo-wide (chiesto: "controlla tutte le Filament Resource")

Grep mirato su tutte e 3 le classi di bug, su tutto `Modules/`:

| Bug class | Pattern cercato | Risultato |
|---|---|---|
| `use` namespace sbagliato (3 varianti) | `use Modules\Xot\Filament\{Resources\|Schemas\|Tables}\XotBaseResource{Form\|Infolist\|Table};` senza il segmento corretto | 0 residui dopo il fix (solo Quotation era colpito) |
| `getFormSchema()`/`getInfolistSchema()` non-static su classi `extends XotBaseResourceForm/Infolist` | grep classi che estendono + assenza di `static function get...Schema` | 2 falsi positivi verificati e scartati: `Xot/tests/Unit/Support/OptionLabelProbeForm.php` e `User/.../Auth/Schemas/UserForm.php` — nessuno dei due sovrascrive il metodo, usano il default vuoto del genitore, non è un bug |
| `getInfolistSchema()` mancante su Page `extends XotBaseViewRecord` | idem | 1 falso positivo: `Lang/.../LangBaseViewRecord.php`, è essa stessa `abstract`, legittimo |

**Conclusione**: i bug fatali erano isolati a `SiteSurveyResource` (probabile:
estrazione a metà, interrotta prima del testing). Non è un pattern sistemico
sull'intero repo al momento di questa verifica — ma **è la stessa categoria di
errore** (rename/estrazione senza verificare che la classe carichi) già vista su
Platform (story 4.2) e va ri-controllata a ogni nuova estrazione del swarm epic 4
(story 4.3), non solo una volta.

## Non committato

Il modulo Quotation ha ~30 file modificati da un altro agente/swarm in questo
momento (estrazione Schemas/Tables in corso su `QuotationResource`,
`QuotationTemplateResource`, oltre a `SiteSurveyResource`). Per `03-quality-gates.md`
regola 8 ("mai committare in un modulo su cui non hai una story assegnata" nel senso
di scope esclusivo) e per non interferire con un lavoro multi-file in corso, i 4 fix
di questa story **restano nel working tree, verificati, non committati da questa
sessione**. Chi possiede il lock/lo swarm su Quotation li raccoglie nel proprio
prossimo commit — sono già coerenti con il resto delle modifiche in corso (stesso
stile, stessi import).

## Status

- **Phase**: Done (fix applicato e verificato, commit lasciato allo swarm attivo sul modulo)
- **Owner**: Quotation
- **Second brain**: vedi `pattern_xotbase_schemas_wrong_namespace_fatal.md`
