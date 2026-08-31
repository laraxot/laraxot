# Structure

# Structure Rules per BMAD create-story

## Regola Fondamentale
In ogni concrete `laravel/Modules/*/app/Filament/Resources/<Name>Resource/` devono esserci:
- `Schemas/<Name>Form.php`
- `Schemas/<Name>Infolist.php`
- `Tables/<PluralName>Table.php`

NON modificare mai:
- `laravel/Modules/*/app/Filament/<name>Resource`

## Reference
Studiare: https://github.com/filamentphp/demo e docs Filament 5 Resources.
`XotBaseResource` auto-discovera Schemas/Tables: il Resource resta orchestratore.

## Pattern Obbligatori

### Infolist (riscrivi l'array, NO delega!)
```php
public static function getInfolistSchema(): array
{
    return [
        'name' => TextEntry::make('name'),
    ];
}
```
L'array non puo' essere vuoto: usare campi reali da model, migration, fillable, form o table.
⚠️ **CHI ESTENDE XotBaseResourceInfolist**:
- IL METODO `getInfolistSchema` DEVE restituire un array con chiavi stringhe.
- NON DEVE MAI RESTITUIRE UN ARRAY VUOTO!
- I campi NON vanno inventati: ricavarli dal modello e dalla migrazione collegata.
- NON deve avere il metodo `configure()`, deve avere `getInfolistSchema()`.

### Form (riscrivi l'array, NO delega!)
```php
public static function getFormSchema(): array
{
    return [
        'name' => TextInput::make('name')->required(),
    ];
}
```
Vietati `return <Name>Resource::getFormSchema()` e `array_filter(<Name>Resource::getFormSchema(), ...)`.
L'array non puo' essere vuoto.

### Table
```php
public static function getTableColumns(): array
{
    return [
        'name' => TextColumn::make('name')->searchable()->sortable(),
    ];
}
```
Chi estende `XotBaseResourceTable` deve restituire array con chiavi stringhe stabili.
Nota: `XotBaseResourceTable` dichiara `getTableColumns()` come metodo abstract - quindi è OBBLIGATORIO per ogni classe che la estende.

## Components Che Esistono ✅
- **Infolist**: TextEntry, IconEntry, ImageEntry, ColorEntry, CodeEntry, KeyValueEntry, RepeatableEntry
- **Form**: TextInput, Select, Textarea, Toggle, KeyValue, RichEditor, MarkdownEditor
- **Table**: TextColumn, ImageColumn, IconColumn, BadgeColumn

## Components Che NON Esistono ❌
- BadgeEntry → usare `TextEntry::make('x')->badge()`
- TextareaEntry → usare `TextEntry::make('x')->limit(100)`
- MarkdownEntry → usare `TextEntry::make('x')->markdown()`
- BooleanEntry → usare `IconEntry::make('x')->boolean()` o TextEntry con badge
- UrlInput → usare `TextInput::make('x')`

## Date Columns
- `->date()` per date
- `->dateTime()` per datetime con ora

## Quality Gates
Dopo ogni modifica: PHPStan `Modules` a 0 errori, PHPMD phar, PHPInsights, Pest. Playwright/Puppeteer solo per runtime UI.

## Script Placement
Audit script vanno in `bashscripts/<category>/`, documentati in `bashscripts/docs/`, poi `qmd update`.

## Array Keys Obbligatorie
Tutti i metodi che restituiscono array DEVONO usare chiavi stringhe:
- `getTableColumns(): array<string, Column>`
- `getFormSchema(): array<string, Component>`
- `getInfolistSchema(): array<string, Component>`
- `getTableFilters(): array<string, BaseFilter>`

Perché:
- Leggibilità: `$columns['name']` vs `$columns[0]`
- Type-safety: PHPStan Level 10
- Manutenzione: ricerca campo più facile
- Consistenza: Form/Infolist/Table usano tutti chiavi stringhe

## Migrazione Step-by-step
Duplicare temporaneamente gli array e' intenzionale: e' il male minore per staccare Schemas/Tables dai Resource legacy senza modificare `*Resource.php`.

## Git
Solo forward-only. Studiare `git log/show`, mai ripristinare vecchi file.

---

# Best Practices ✅

## Naming Conventions
- Resource: `<Name>Resource` (es. `ArticleResource`)
- Form Schema: `<Name>Form` (es. `ArticleForm`)
- Infolist Schema: `<Name>Infolist` (es. `ArticleInfolist`)
- Table: `<PluralName>Table` (es. `ArticlesTable`)

## Ordine Componenti nel Form
1. Section/Grid per raggruppamento logico
2. TextInput per campi primari
3. Select per opzioni
4. Toggle per booleani
5. DatePicker per date
6. FileUpload per media
7. RichEditor per testi lunghi

## Ordine Colonne Table
1. ID o chiave primaria (spesso nascosta)
2. Campi visualizzati sempre (nome, titolo)
3. Campi cercabili/ordinabili
4. Azioni (ultima colonna)

## Type Safety
- Usare sempre `array<string, T>` per metodi che restituiscono array associativo
- Non usare mai array indicizzati numericamente
- Preferire return type declaration espliciti

## Filament Conventions
- Usare `make()` non `new` per istanziare componenti
- Configurazione tramite method chaining (fluent interface)
- Usare `Get $get` per lettura valori dinamici
- Usare `Set $set` per modifiche dinamiche

---

# Bad Practices ❌

## Delegazione Proibita
```php
// ❌ MAI - delega a Resource
public static function getFormSchema(): array
{
    return ArticleResource::getFormSchema();
}

// ❌ MAI - array_filter su metodo del Resource
public static function getFormSchema(): array
{
    return array_filter(ArticleResource::getFormSchema(), fn($k) => $k !== 'id');
}
```

## Array Vuoti Proibiti
```php
// ❌ MAI - array vuoto senza campi
public static function getInfolistSchema(): array
{
    return [];
}
```

## Chiavi Numeriche Proibite
```php
// ❌ MAI - array con chiavi numeriche
public static function getTableColumns(): array
{
    return [
        0 => TextColumn::make('name'),
        1 => TextColumn::make('email'),
    ];
}
```

## Componenti Inesistenti
```php
// ❌ MAI - usare componenti che non esistono
use Filament\Infolists\Components\BadgeEntry;  // NON ESISTE

// ❌ MAI - usare classi non esistenti
TextInput::make('url');  // Non esiste UrlInput
```

## Label/Tooltip Manuali
```php
// ❌ MAI - label/tooltip nel componente
TextInput::make('name')
    ->label('Nome')           // NO - LangServiceProvider gestisce
    ->tooltip('Inserisci nome'); // NO - LangServiceProvider gestisce
```

## Style Inline in Blade
```php
// ❌ MAI - style inline nei componenti del modulo
<div style="margin-top: 1rem;">...</div>  // NO - tema gestisce CSS

// ✅ CORRETTO - usare classi Tailwind
<div class="mt-4">...</div>
```

## Modificare Resource Legacy
```php
// ❌ MAI - modificare il Resource principale
class ArticleResource extends XotBaseResource
{
    // NON toccare - Schemas/Tables sono auto-discovered
}
```

---

# False Friends 🤥

| Scrittura Sbagliata | Scrittura Corretta | Perché |
|---------------------|-------------------|--------|
| `BadgeEntry::make('status')` | `TextEntry::make('status')->badge()` | BadgeEntry non esiste in Filament |
| `BooleanEntry::make('active')` | `IconEntry::make('active')->boolean()` | BooleanEntry non esiste |
| `TextareaEntry::make('desc')` | `TextEntry::make('desc')->limit(100)` | TextareaEntry non esiste |
| `MarkdownEntry::make('body')` | `TextEntry::make('body')->markdown()` | MarkdownEntry non esiste |
| `UrlInput::make('website')` | `TextInput::make('website')` | UrlInput non esiste |
| `Select::make('type')->options([])` | `Select::make('type')->options(OptionEnum::class)` | Preferire enum |
| `Table::make()->columns([])` | `static function getTableColumns(): array` | Pattern XotBase |
| `$columns[0]` | `$columns['name']` | Chiavi stringhe obbligatorie |
| `->visible(fn)` | `->visible(fn (Get $get): bool => ...)` | Usare Get utility |
| `Action::make('delete')` | `DeleteAction::make()` | Azioni predefinite esistono |

---

# Links Verificati 📚

## Filament Official
- Docs: https://filamentphp.com/docs
- Demo App: https://github.com/filamentphp/demo/tree/5.x
- Package: https://packagist.org/packages/filament/filament

## Filament v5 Schema Structure
- Schemas: https://github.com/filamentphp/filament/blob/5.x/packages/schemas/src/Components/Concerns/HasSchema.php
- Wizard: https://github.com/filamentphp/filament/blob/5.x/packages/schemas/src/Components/Wizard.php

## Laravel Best Practices
- Laravel: https://laravel.com/docs/12.x
- Laravel Pennant: https://laravel.com/docs/12.x/pennant
- Laravel Folio: https://laravel.com/docs/12.x/folio

## Testing
- Pest: https://pestphp.com/docs
- Filament Testing: https://filamentphp.com/docs/testing

## Quality Tools
- PHPStan: https://phpstan.org/
- PHPMD: https://phpmd.org/
- Pint: https://github.com/laravel/pint

---

# Correzioni Frequenti 🔧

## Toggle vs Checkbox
```php
// ✅ CORRETTO per booleani in Form
Toggle::make('is_active')
    ->onLabel('Attivo')
    ->offLabel('Disattivo');

// ✅ CORRETTO per booleani in Infolist
TextEntry::make('is_active')
    ->badge()
    ->colors(['active' => 'success', 'inactive' => 'danger']);
```

## Date Handling
```php
// ✅ CORRETTO - date semplice
TextColumn::make('created_at')
    ->date();  // Format: d/m/Y

// ✅ CORRETTO - datetime con ora
TextColumn::make('created_at')
    ->dateTime();  // Format: d/m/Y H:i
```

## Searchable/Sortable
```php
// ✅ CORRETTO - colonna ricercabile e ordinabile
TextColumn::make('title')
    ->searchable()
    ->sortable();

// ✅ CORRETTO - colonna solo display
TextColumn::make('body')
    ->limit(100)
    ->toggleable();
```

## Enum in Select
```php
// ✅ CORRETTO - usare enum per options
use Modules\Blog\Enums\PostStatus;

Select::make('status')
    ->options(PostStatus::class)
    ->enum(PostStatus::class);
```

## Relationship in Table
```php
// ✅ CORRETTO - relazione belongsTo
TextColumn::make('author.name')
    ->searchable()
    ->sortable();

// ✅ CORRETTO - relazione hasMany (count)
TextColumn::make('comments_count')
    ->counts('comments');
```

## Live() per Dipendenze
```php
// ✅ CORRETTO - campo dipendente da altro
Select::make('category_id')
    ->options(Category::class)
    ->live(),

TextInput::make('subcategory')
    ->visible(fn (Get $get): bool => $get('category_id') !== null);
```

---

# Note Importanti 📝

## Obbligatorietà Metodi Abstract

### XotBaseResourceTable
Il metodo `getTableColumns()` è dichiarato come `abstract` in `XotBaseResourceTable` - quindi ogni classe che estende questa classe astratta **DEVE** implementare questo metodo. Questo garantisce type-safety e PHPStan Level 10 compliance.

### XotBaseResourceInfolist
Il metodo `getInfolistSchema()` è dichiarato come `abstract` in `XotBaseResourceInfolist` - quindi ogni classe che estende questa classe astratta **DEVE** implementare questo metodo con almeno un campo. L'array NON può essere vuoto!

## Auto-Discovery Pattern
XotBaseResource usa magic methods per auto-discoverare Schemas e Tables:
- `getFormSchema()` → cerca `Schemas/<Name>Form.php`
- `getInfolistSchema()` → cerca `Schemas/<Name>Infolist.php`
- `getTableColumns()` → cerca `Tables/<PluralName>Table.php`

NON modificare mai i file Resource principali - solo creare Schemas/Tables.

## LangServiceProvider
Le traduzioni sono gestite da LangServiceProvider. MAI usare `->label()` o `->tooltip()` nei componenti - le traduzioni sono caricate automaticamente dai file di lingua.

## Regola Arrays Non Vuoti
L'array restituito da `getInfolistSchema()` **NON può essere vuoto**.
Per determinare i campi corretti:
1. Studiare il **Model** collegato (`app/Models/<Name>.php`)
2. Studiare la **Migration** (`database/migrations/*_create_<names>_table.php`)
3. Consultare `$fillable` nel model
4. Usare i campi reali del database, NON inventare campi

Esempio corretto - studiare model/migration:
```php
// Model: app/Models/Article.php -> $fillable = ['title', 'slug', 'body', 'published_at', 'author_id']
// Migration: 2024_01_01_000001_create_articles_table.php
public static function getInfolistSchema(): array
{
    return [
        'title' => TextEntry::make('title'),
        'slug' => TextEntry::make('slug'),
        'published_at' => TextEntry::make('published_at')->dateTime(),
    ];
}
```

## Regola Table getTableColumns
L'array restituito da `getTableColumns()` **NON può essere vuoto**.
- Chiavi **OBBLIGATORIE** sono stringhe (es. `'title'`, `'created_at'`)
- **NON inventare campi** - studiare:
  1. Il **Model** collegato (`app/Models/<Name>.php`)
  2. La **Migration** (`database/migrations/*_create_<names>_table.php`)
  3. `$fillable` nel model
  4. Usare i campi reali del database

**XotBaseResourceTable** già rende `getTableColumns()` come metodo **abstract** - quindi ogni classe che estende **DEVE** implementarlo con array non vuoto.

---

## Audit Script (Single Source of Truth)
Path canonico: `bashscripts/quality/audit-filament-resource-structure.php`
Doc: `bashscripts/docs/quality/filament-resource-structure-audit.md`
NON in `laravel/scripts/`. NON duplicare in `bashscripts/filament/`.

## Verifica Filesystem Prima di Asserire
Prima di scrivere "ComponentX esiste ✅":
```bash
ls laravel/vendor/filament/{infolists,forms,schemas,tables}/src/Components/
find laravel/vendor/filament -name "ComponentX.php"
```
