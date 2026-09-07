---
id: STORY-2026-08-HASXOTFORM-INSTANCE
slug: hasxotform-instance-columns
title: "HasXotForm: align static getFormSchemaColumns() to instance $this->getFormSchemaColumns()"
status: proposed
module: Xot
branch: dev
story_type: refactor
assigned_agents: [pi, bmad-architect]
priority: high
---

# HasXotForm: static → instance alignment

## Problema
- `Modules/Xot/app/Filament/Traits/HasXotForm.php` usa `->columns($this->getFormSchemaColumns())` (istanza).
- `Modules/Xot/app/Filament/Resources/Schemas/XotBaseResourceForm.php` usa `->columns(static::getFormSchemaColumns())` (statico).
- Questa incoerenza rompe il pattern: PHPStan, Filament, e qualunque subclass si aspetta che il Base segua la stessa logica del Trait.

## Cosa fare
1. `XotBaseResourceForm::configure()` → usare `$instance = app(static::class)` e poi `->columns($instance->getFormSchemaColumns())`.
2. `XotBaseResourceForm::getFormSchemaColumns()` → non più static, metodo d'istanza.
3. `XotBaseResourceForm::getFormSchema()` → refactoring coerente (istanza, non static).
4. `XotBaseResourceForm::getSteps()` → idem (istanza).
5. `XotBaseResourceForm::optionLabelFromRecord()` → `protected function` non `protected static`.
6. `XotBaseResourceForm::getStepByName()` → non static.

## Pattern (riferimento Table)
```php
// Modules/Xot/app/Filament/Resources/Tables/XotBaseResourceTable.php
public static function configure(Table $table): Table
{
    if (static::class === self::class) {
        throw new \LogicException('XotBaseResourceTable::configure() must be called on a concrete table class.');
    }
    $instance = app(static::class);
    Assert::isInstanceOf($instance, self::class);
    return $instance->table($table);
}
```

## Acceptance Criteria
- [ ] `XotBaseResourceForm::configure()` istanzia `$instance` e chiama metodi istanza
- [ ] `getFormSchemaColumns()` non static
- [ ] `getFormSchema()` non static (come da `HasXotForm` trait)
- [ ] `getSteps()` non static
- [ ] `optionLabelFromRecord()` non static
- [ ] `getStepByName()` non static
- [ ] Tutti i subclass aggiornati di conseguenza
- [ ] `phpstan analyse Modules/Xot` → 0 errori
- [ ] `phpstan.neon` non modificato

## GitHub Issue
```
Title: [refactor] HasXotForm / XotBaseResourceForm: align static → instance pattern
Body: vedi BMAD story STORY-2026-08-HASXOTFORM-INSTANCE
Labels: refactor, xot, hasxotform
Repo: laraxot/module_xot_fila5
```

## GitHub Discussion
Aprire: `Architecture: HasXotForm instance pattern + XotBaseResourceForm parity with Table`

## Anti-pattern vietati
- Mai `#[Override]` senza metodo genitore
- Mai `phpstan.neon` modifiche
- Mai `git push --force`

## Rischi
- Tutte le classi `*Form` che estendono `XotBaseResourceForm` devono essere adeguate.
- Coordinare con altri agenti (User module, Compliance, etc.).
