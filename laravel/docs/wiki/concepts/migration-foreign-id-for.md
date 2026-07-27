---
title: "foreignIdFor senza constrained(): FK verso users e tabelle cross-modulo"
type: "concept"
tags:
  - laraxot
  - migration
  - foreignIdFor
  - users
  - multi-database
created: "2026-07-27"
updated: "2026-07-27"
qmd: "foreignIdFor constrained users workorder_user workorder_data multi database foreign key"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
---

# `foreignIdFor` senza `constrained()`: perché

## Il fatto architetturale

Questo progetto usa **due database fisici separati sulla stessa connessione MySQL**:

| Database | Contenuto | Connection Laravel |
|---|---|---|
| `workorder_data` | Dati di business (tutti i moduli tranne l'identità utente) | `mariadb` (default) / `xot` |
| `workorder_user` | `users` e tabelle di identità | `user` (`Modules\User\Models\User::getConnectionName()`) |

Verifica: `(new \Modules\User\Models\User())->getConnectionName()` → `'user'` →
`config('database.connections.user.database')` → `workorder_user`.

## Perché questo causa errori

Un vincolo `FOREIGN KEY` MySQL/InnoDB **non può attraversare due database diversi**
in modo portabile con la sintassi standard di Laravel (`->constrained('users')`
assume che `users` sia nello stesso database della tabella che stai creando).
Il sintomo tipico:

```
SQLSTATE[HY000]: General error: 1824 Failed to open the referenced table 'users'
```

anche se `users` esiste — perché esiste in `workorder_user`, non in `workorder_data`.

## Filosofia / politica / religione / zen

| Livello | Intent |
|---|---|
| Religione | 1 colonna FK = tipo coerente col modello referenziato, sempre |
| Politica | L'identità utente (`workorder_user`) è un dominio isolato dal business data (`workorder_data`): segregazione intenzionale, non un bug |
| Logica | Un vincolo DB reale è impossibile/fragile cross-database → l'integrità referenziale su `user_id` si applica a livello Eloquent/applicativo, non a livello DB |
| Zen | Non forzare ciò che l'architettura ha deciso di separare: rispetta il confine tra dati e identità |

## Pattern corretto

```php
use Modules\Xot\Datas\XotData;

$this->tableCreate(function (Blueprint $table): void {
    $user_class = XotData::make()->getUserClass();

    $table->id();
    $table->foreignIdFor($user_class, 'created_by_user_id')->nullable()->index();
    // NIENTE ->constrained('users') qui.
});
```

`foreignIdFor($user_class, $column)` legge dinamicamente il tipo di chiave primaria
del modello User configurato (oggi `string(36)` / UUID, vedi
`Modules/User/database/migrations/*_create_users_table.php`) e crea la colonna con
il tipo corretto — senza assumere bigint né tentare un vincolo cross-database.

## Quando invece `constrained()` è corretto

Per FK verso tabelle **nello stesso database** (`workorder_data`), `constrained()`
va benissimo — ma solo se la migration del genitore ha già girato. Per FK
cross-modulo con genitore "recente" (es. `vehicles`, `work_orders`, `customers`
create il 2026-07-23+), vedi il pattern di riparazione idempotente descritto in
[[bugfix-warehouses-vehicle-fk-order]] (`Modules/Inventory/docs/bugfix-warehouses-vehicle-fk-order.md`):
colonna via `foreignIdFor` in `tableCreate`, vincolo reale aggiunto a fine `up()`
solo se la tabella genitore esiste e il vincolo manca ancora.

## Checklist agente

- [ ] Il campo si chiama `*_user_id`? → mai `->constrained('users')`, sempre `foreignIdFor($user_class, ...)`
- [ ] Il campo referenzia una tabella di un altro modulo con timestamp migration più recente? → vedi pattern idempotente in Inventory
- [ ] Prima di aggiungere `->constrained()`, verificare `php artisan migrate:status` per l'ordine reale
