---
id: STORY-2026-08-HASXOTFORM-COLUMNS-INSTANCE
slug: hasxotform-getformschemacolumns-instance
title: STRUCTURAL REFACTOR — XotBaseResourceForm: getFormSchemaColumns() instance + pattern replication in XotBaseResourceInfolist
status: proposed
module: Xot
branch: dev
remote: laraxot/module_xot_fila5
priority: high
assigned_agents: [pi, bmad-architect]
---

# Refactor — getFormSchemaColumns() static → instance + HasXotForm pattern

## Problema
- `Modules/Xot/app/Filament/Traits/HasXotForm.php` ha `getFormSchemaColumns()` istanza con default `1` ma il `form()` chiama `->columns(2)` hardcoded.
- `Modules/Xot/app/Filament/Resources/Schemas/XotBaseResourceForm.php` ha `getFormSchemaColumns()` statico, `getFormSchema()` statico, `configure()` statico.
- `Modules/Xot/app/Filament/Resources/Schemas/XotBaseResourceInfolist.php` ha già `configure()` statico (refactor già parziale via STORY-2026-08-STRUCTURAL-XOT-FORM-INFO).

## Soluzione
1. `XotBaseResourceForm::configure()` → `$instance = app(static::class)` → `return $instance->form($schema)` (come Table).
2. `XotBaseResourceForm::getFormSchemaColumns()` → non static, default 1.
3. `XotBaseResourceForm::getFormSchema()` → non static.
4. `XotBaseResourceForm::getSteps()` → non static.
5. `XotBaseResourceForm::optionLabelFromRecord()` → `protected function` non `protected static`.
6. `XotBaseResourceForm::getStepByName()` → non static.
7. `XotBaseResourceForm` deve `use HasXotForm`.
8. `XotBaseResourceInfolist` → già parzialmente rifatto, verificare coerenza completa.
9. `HasXotForm` trait → `form()` deve usare `->columns($this->getFormSchemaColumns())` invece di `->columns(2)`.

## GitHub Issue
```
Title: [refactor] XotBaseResourceForm / HasXotForm: static → instance, getFormSchemaColumns() dynamic
Body: BMAD story STORY-2026-08-HASXOTFORM-COLUMNS-INSTANCE
Repo: laraxot/module_xot_fila5
Labels: refactor, xot, hasxotform, structural
```

## GitHub Discussion
`laraxot/module_xot_fila5` → "Architectural: HasXotForm / HasXotInfolist instance pattern + dynamic columns"

## Acceptance Criteria
- [x] `HasXotForm::form()` chiama `->columns($this->getFormColumns())` (FATTO in questa sessione)
- [x] `HasXotForm::getFormColumns(): int` esiste, default 2 (FATTO in questa sessione)
- [x] `XotBaseResourceTable.php` rimosso `use TransTrait` (FATTO — `TransTrait` già in `HasXotTable`)
- [ ] `XotBaseResourceForm::configure()` istanza via `$instance = app(static::class)`
- [ ] `XotBaseResourceForm::getFormSchema()` non static
- [ ] `XotBaseResourceForm::getFormSchemaColumns()` non static
- [ ] `XotBaseResourceForm::getSteps()` non static
- [ ] `XotBaseResourceForm::optionLabelFromRecord()` non static
- [ ] `XotBaseResourceForm::getStepByName()` non static
- [ ] `XotBaseResourceForm` uses `HasXotForm`
- [ ] `XotBaseResourceInfolist` allineato allo stesso pattern (già parziale)
- [ ] `phpstan analyse Modules/Xot` → 0 errori
- [ ] `phpstan.neon` non modificato
- [ ] docs aggiornati

## Pattern di riferimento
```php
// XotBaseResourceTable (già corretto)
public static function configure(Table $table): Table
{
    if (static::class === self::class) {
        throw new \LogicException('...');
    }
    $instance = app(static::class);
    Assert::isInstanceOf($instance, self::class);
    return $instance->table($table);
}

// HasXotForm (già corretto in questa sessione)
public function getFormColumns(): int
{
    return 2;
}
final public function form(Schema $schema): Schema
{
    return $schema
        ->components($this->getFormSchema())
        ->columns($this->getFormColumns())
        ->statePath('data');
}
```

## Anti-pattern vietati
- Mai `#[Override]` senza parent
- Mai `phpstan.neon` modifiche
- Mai `git push --force`
- Mai `dddx` in produzione (è gia in XotBaseResourceForm)

## Rischi
- Tutte le `*Form.php` e `*Infolist.php` di tutti i moduli devono essere adeguate.
- Coordinamento con altri agenti (User/Compliance/Catalog ecc.).

## Git commit
```
refactor(xot): HasXotForm / XotBaseResourceForm / XotBaseResourceInfolist instance pattern
[story: HASXOTFORM-COLUMNS-INSTANCE]
```

## Git push
```
git push -u laraxot dev
```
