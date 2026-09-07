---
id: filament-canonical-structure
slug: filament-canonical-structure
title: "Filament Resource canonical structure (Laraxot)"
description: "Pattern obbligatorio per Filament Resource in Laraxot: extends XotBaseResource diretto, Schemas/<Name>Form+Infolist, Tables/<Plural>Table, zero label hardcoded, LangServiceProvider per tutte le label/tooltip/placeholder."
document_type: pattern
category: filament
status: active
version: 2.0.0
language: it-IT
project: base_workorder_fila5
domain: filament
source_of_truth: true
github_issue_url: https://github.com/laraxot/bashscripts_fila5/issues/10
github_discussion_url: https://github.com/laraxot/bashscripts_fila5/discussions/14
created_at: '2026-09-07'
updated_at: '2026-09-07'
tags:
  - filament
  - laraxot
  - xotbase
  - resources
  - schemas
  - label
  - langserviceprovider
depends_on:
  - ../bashscripts/docs/prompts/06-filament-audit.md
  - ../bashscripts/docs/prompts/21-translations.md
  - ../bashscripts/docs/prompts/55-md-conventions.md
related:
  - laravel/Modules/Xot/app/Filament/Resources/XotBaseResource.php
  - laravel/Modules/Xot/app/Filament/Resources/Schemas/XotBaseResourceForm.php
  - laravel/Modules/Xot/app/Filament/Resources/Schemas/XotBaseResourceInfolist.php
  - laravel/Modules/Xot/app/Filament/Resources/Tables/XotBaseResourceTable.php
  - laravel/Modules/Xot/app/Filament/Traits/HasXotTable.php
---

# Filament canonical structure

## TL;DR

- Estendi **SOLO** `XotBaseResource` (mai `Filament\Resources\Resource`, **mai** varianti inventate tipo `GatedXotBaseResource`).
- Schema form → `Schemas/<Name>Form extends XotBaseResourceForm`.
- Schema infolist → `Schemas/<Name>Infolist extends XotBaseResourceInfolist`.
- Le sottoclassi dirette o indirette di `XotBaseViewRecord` **non dichiarano `getInfolistSchema()`**: la pagina delega alla Resource tramite `infolist(Schema)`. Rimuovere il metodo obsoleto, non soltanto `#[\Override]`; mantenere lo schema nella classe dedicata.
- Tabella → `Tables/<Plural>Table extends XotBaseResourceTable`.
- **ZERO** `->label(...)` / `->tooltip(...)` / `->placeholder(...)` hardcoded. Tutto autoregistrato da `LangServiceProvider` con chiave `<modulo>.<resource>.<field>.label` / `.tooltip` / `.placeholder`.

## Anatomia

```text
Modules/<M>/app/Filament/Resources/
  <Name>Resource.php                                  extends XotBaseResource
  <Name>Resource/
    Schemas/<Name>Form.php                            extends XotBaseResourceForm
    Schemas/<Name>Infolist.php                        extends XotBaseResourceInfolist
    Tables/<PluralName>Table.php                      extends XotBaseResourceTable
    Pages/List<Plural>.php                            extends XotBaseListRecords
    Pages/Create<Name>.php                            extends XotBaseCreateRecord
    Pages/Edit<Name>.php                              extends XotBaseEditRecord
    Pages/View<Name>.php                              extends XotBaseViewRecord
    RelationManagers/<Rel>RelationManager.php         extends XotBaseRelationManager
```

## Naming convention (auto-scoping di XotBaseResource)

| Elemento | FQCN atteso da `XotBaseResource` |
|----------|---------------------------------|
| Form | `static::class.'\Schemas\\'.class_basename(static::getModel()).'Form'` |
| Infolist | `static::class.'\Schemas\\'.class_basename(static::getModel()).'Infolist'` |
| Table | `static::class.'\Tables\\'.Str::plural(class_basename(static::getModel())).'Table'` |

Vedi `XotBaseResource.php:138-167`.

## Regole non negoziabili

### 1. Estensioni

```php
// ✅ CORRETTO
final class FooResource extends \Modules\Xot\Filament\Resources\XotBaseResource { }

// ❌ VIETATO — Filament diretto
final class FooResource extends \Filament\Resources\Resource { }

// ❌ VIETATO — varianti inventate (Gated, Auth, ecc.)
final class FooResource extends GatedXotBaseResource { }
final class FooResource extends \Modules\Platform\Filament\Resources\GatedXotBaseResource { }
```

**Perché**: `XotBaseResource` è l'unica fonte canonica dell'auto-scoping. Qualsiasi sottoclasse intermedia rischia di rompere l'auto-routing. L'autorizzazione va gestita con `Gate`/`Policy`/`Spatie Permission`, non con il naming.

### 2. Traduzioni: ZERO `->label()`

Tutte le label sono autoregistrate da `LangServiceProvider` partendo dal nome del campo. Una label hardcoded è un bug di traduzione mascherato da UI funzionante.

```php
// ✅ CORRETTO — label auto da fields.supplier.label
Forms\Components\Select::make('supplier_id')->required()

// ❌ VIETATO — qualsiasi ->label()
Forms\Components\Select::make('supplier_id')
    ->label(__('billing::suppliers.fields.supplier'))  // MAI
    ->label('Fornitore')                                // MAI peggio
    ->label('Supplier')                                 // MAI peggio
```

Convenzione chiavi lang: `<modulo>.<resource>.<field>.label` (es. `billing.supplier.legal_name.label`). Le chiavi dei file di traduzione modulo vivono in `Modules/<M>/lang/it/<file>.php`.

### 3. Struttura canonica obbligatoria

`XotBaseResource::form()`, `infolist()` e `table()` cercano classi dedicate. Se mancano:
- Form → fallback su `getFormSchema()` statico (legacy bridge)
- Infolist → array vuoto
- Table → fallback su `getTableClass()` action che delega ad altra Resource registrata

**Gap** = struttura canonica mancante. Il prompt `06-filament-audit.md` §1 lo rileva con `find` + `ls`.

### 4. Resource = solo metadata, NO schema/table inline

```php
// ✅ CORRETTO — Resource snella, delega a Schemas/Tables
final class FooResource extends XotBaseResource
{
    protected static ?string $model = Foo::class;
    protected static ?string $navigationIcon = 'heroicon-o-foo';

    public static function getNavigationGroup(): string
    {
        return __('foo::foo.navigation');
    }
}

// ❌ VIETATO — schema/table inline
final class FooResource extends XotBaseResource
{
    public static function getFormSchema(): array { return [...]; }
    public static function table(Table $t): Table { return $t->columns([...]); }
}
```

### 5. Table = no override `table()`

`XotBaseResourceTable` estende `HasXotTable` (`HasXotTable.php:220`) che fornisce già `table()`. Ridefinirlo rompe il flusso.

```php
// ✅ CORRETTO — solo hook consentiti
final class FooTable extends XotBaseResourceTable
{
    public function getTableColumns(): array { return [...]; }
    public function getTableFilters(): array { return [...]; }
    public function getTableActions(): array { return [...]; }
}

// ❌ VIETATO — override table()
class FooTable extends XotBaseResourceTable
{
    public function table(Table $t): Table { return ...; }
}
```

## Algoritmo gap detection (filesystem-first)

```bash
# 1. Ricognizione — MAI fidarsi della memoria, sempre find
find Modules/<M>/app/Filament/Resources -type d 2>/dev/null | while read r; do
  [ -d "$r/Schemas" ] || echo "❌ $r/Schemas MANCANTE"
  [ -d "$r/Tables" ]  || echo "❌ $r/Tables MANCANTE"
done

# 2. Anti-pattern grep
grep -rln "extends GatedXotBaseResource\|extends \\\\Filament\\\\Resources\\\\Resource" Modules/<M>/app/Filament/
grep -rln "->label(__(\|->label('" Modules/<M>/app/Filament/
grep -rln "function getFormSchema\|function getInfolistSchema\|function table(" Modules/<M>/app/Filament/Resources/*.php
```

## Lezione operativa (postmortem 2026-09-07)

Errore commesso: scritto prompt di audit basandomi sulla conoscenza astratta, non sul filesystem. Il prompt 06-filament-audit.md v2 dichiarava la struttura canonica ma NON la verificava automaticamente. Conseguenza: 4 Resources Billing passate con gap strutturali + 30+ `->label()` hardcoded + estensione `GatedXotBaseResource` vietata.

Correzione: ogni prompt che descrive uno stato atteso del codice **DEVE** avere uno step `find`/`grep` che lo verifica prima di qualsiasi edit. Il prompt `06-filament-audit.md` v4 incorpora questo pattern.

## Cross-ref

- `bashscripts/docs/prompts/06-filament-audit.md` — prompt operativo (v4+ con gap detection)
- `bashscripts/docs/prompts/21-translations.md` — "Filament: niente label manuali"
- `bashscripts/docs/prompts/55-md-conventions.md` — convenzioni globali del monorepo
- `laravel/Modules/Billing/docs/stories/4.1.filament-resource-gap-billing.story.md` — story BMAD con caso reale

## Esempio canonico di riferimento

`laravel/Modules/Gdpr/app/Filament/Resources/ConsentResource/` — ha `Schemas/ConsentForm.php`, `Schemas/ConsentInfolist.php`, `Tables/ConsentsTable.php`, tutti correttamente strutturati. Usare come template.
