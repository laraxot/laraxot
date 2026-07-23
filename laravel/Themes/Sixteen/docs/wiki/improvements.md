---
title: "Miglioramenti"
type: improvements
created: 2026-04-28
updated: 2026-04-28
tags: [improvements, coordinate-picker, state-binding, migration-fix]
---

# Miglioramenti Recenti

## 2026-04-28: CoordinatePicker State Binding Fix

### Problema
In `coordinate-picker.blade.php` veniva utilizzata la sintassi errata per il binding dello stato Livewire:

```blade
{{-- SBAGLIATO --}}
state: $wire.$entangle('{{ $getStatePath() }}'),
```

Questo ignorava i modificatori `live()` e `defer()` impostati sul field PHP, comportando che le modifiche alle coordinate non venivano sincronizzate immediatamente quando richiesto.

### Soluzione
Implementata la sintassi corretta conforme a Filament 5.x:

```blade
state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
```

La funzione `$applyStateBindingModifiers()` trasforma `$entangle` in:
- `$entangleLive` quando il field usa `->live()`
- `$entangleDeferred` quando il field usa `->defer()` (default)
- `$entangle` per il comportamento standard

### Riferimenti
- **File modificato**: `laravel/Modules/Geo/resources/views/filament/forms/components/coordinate-picker.blade.php`
- **Documentazione**: [Custom Fields - Obeying state binding modifiers](https://filamentphp.com/docs/5.x/forms/custom-fields)

## 2026-04-28: MariaDB Migration Compatibility Fix

### Problema
La migration `2026_04_28_120000_create_profiles_table.php` falliva su MariaDB con errore SQL 1064:

```sql
SQLSTATE[42000]: Syntax error or access violation: 1064 near 'after `id`, ...'
```

### Root Cause
Uso di `->after('colonna')` nella definizione delle colonne, sintassi non supportata da MariaDB durante la creazione della tabella.

### Soluzione
Rimossi tutti i posizionamenti `after()` dalla migration:
- Colonne aggiunte in ordine naturale
- Nessun riferimento a `->after()`
- Migration ora compatibile sia MySQL che MariaDB

### File Modificato
- `laravel/Modules/User/database/migrations/2026_04_28_120000_create_profiles_table.php`

### Verifica
```bash
php artisan migrate --path=Modules/User/database/migrations/2026_04_28_120000_create_profiles_table.php
# Risultato: Nothing to migrate. ✓
```

## 2026-04-28: Two-Level Save Pattern per CoordinatePicker

### Implementazione
Per CoordinatePicker con colonne separate `latitude`/`longitude`:

**Livello 1** (`HasCoordinatePicker` trait):
- Usa `dehydrateStateUsing()` per normalizzare lo stato composito
- Filtra i campi UI-only (address, display_name, etc.)
- Restituisce solo `{latitude, longitude}`
- **VIETATO**: `dehydrated(false)` (blocca il salvataggio)

**Livello 2** (Modello Eloquent):
- Mutator `location(): Attribute` smista le coordinate in colonne separate
- Get: legge `latitude` e `longitude` dal DB
- Set: riceve array `['latitude' => X, 'longitude' => Y]` e salva nelle colonne

### File Coinvolte
- Trait: `laravel/Modules/Geo/app/Filament/Forms/Components/Traits/HasCoordinatePicker.php`
- Modello: `laravel/Modules/Fixcity/app/Models/Ticket.php`

### Documentazione
- [CoordinatePicker Filament 5 Save Pattern](../../../../Modules/Geo/docs/wiki/concepts/coordinate-picker-filament5-save-pattern.md)
- [Coordinate Picker State Binding Rule](../../../../bashscripts/ai/.claude/rules/coordinate-picker-state-binding-rule.md)

---

## Collegamenti Utili

- [Laravel Boost MCP Server Docs](../../../../bashscripts/ai/.claude/rules/laravel-boost-mcp-server.md)
- [Regole Progetto](../../../../bashscripts/ai/.claude/rules/)
- [Wiki Modulo Geo](../../../../Modules/Geo/docs/wiki/)
- [Wiki Modulo User](../../../../Modules/User/docs/wiki/)
