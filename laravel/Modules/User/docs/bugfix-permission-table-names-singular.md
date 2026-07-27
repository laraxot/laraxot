---
title: "Bugfix: spatie/laravel-permission table_names singular/plural mismatch"
type: bugfix
module: User
tags: [permission, spatie, roles, config, model_has_roles]
created: 2026-07-27
updated: 2026-07-27
issues:
  - "https://github.com/laraxot/module_user_fila5/issues/73"
related:
  - ../../../config/permission.php
  - ./config/local/workorder/permission.php
---

# spatie/laravel-permission — nomi tabella singolare vs plurale

## Sintomo

`SQLSTATE[42S02]: Base table or view not found: 1146 Table 'workorder_user.model_has_roles' doesn't exist`
in `Spatie\Permission\Traits\HasRoles::hasRole()` → `roles()` → `Spatie\Permission\Support\Config::modelHasRolesTable()`.

## Causa reale (verificata leggendo il codice sorgente del package, non ipotizzata)

`Spatie\Permission\Support\Config::modelHasRolesTable()` (vendor/spatie/laravel-permission/src/Support/Config.php:42-45)
fa semplicemente `config('permission.table_names.model_has_roles')` — nessuna cache, nessun fallback.
Il default upstream del package (vendor/spatie/laravel-permission/config/permission.php) è **plurale**
per tutte e tre le pivot: `model_has_permissions`, `model_has_roles`, `role_has_permissions`.

Questo progetto ha attraversato più round di modifiche a `config/permission.php` (e al suo
overlay tenant `config/local/workorder/permission.php`) durante lo stesso incidente, in due
direzioni opposte, prima di stabilizzarsi. **Stato finale verificato (2026-07-27, dopo
l'incidente completo raccontato sotto)**:

| Config key | Valore finale (root + overlay) | Tabella live esistente | Corretto? |
|---|---|---|---|
| `model_has_permissions` | `model_has_permissions` (plurale) | `model_has_permissions` (plurale) | ✅ |
| `model_has_roles` | `model_has_roles` (plurale) | `model_has_roles` (plurale, 41 righe preservate) | ✅ |
| `role_has_permissions` | `role_has_permissions` (plurale) | `role_has_permissions` (plurale) | ✅ |

Il pattern finale corretto **è** uniforme: tutte e tre plurale, coerente con il default upstream
del package (`vendor/spatie/laravel-permission/config/permission.php`) e con i nomi dei tre file
migrazione (`create_model_has_roles_table.php`, `create_model_has_permissions_table.php`,
`create_role_has_permissions_table.php` — tutti già plurale nel filename). Una versione precedente
di questa nota descriveva `model_has_roles`/`role_has_permissions` come "correttamente singolare":
era la fotografia di uno stato intermedio dell'incidente (Passo 1 sotto), non lo stato finale —
non fidarsi di quella tabella se la si trova citata altrove, verificare sempre live come descritto
in "Come verificare in futuro".

## Fix

`config/permission.php` (root) e `config/local/workorder/permission.php` (overlay tenant)
allineati allo stesso valore plurale per tutte e tre le chiavi, coerente con lo schema fisico
reale (rinominato una sola volta, vedi Passo 2/3 sotto — non con lo schema fisico "di comodo").

## Come verificare in futuro

```bash
php -r '
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
foreach (["model_has_roles","model_has_permissions","role_has_permissions"] as $k) {
  echo $k." => ".config("permission.table_names.$k").PHP_EOL;
}
'
```

Confrontare l'output con `SHOW TABLES` sulla connection `user` (`workorder_user`), non assumere.

## Nota multi-agente (cronologia reale dell'incidente)

Il valore di `model_has_roles` è stato cambiato più volte da agenti diversi nella stessa sessione,
in due direzioni opposte, prima di stabilizzarsi. Cronologia per intero, perché la seconda parte è
l'errore più grave e va evitato in futuro:

1. **Passo 1 (io, sbagliato)**: verificando `model_has_permissions` (plurale) e `role_has_permissions`
   (singolare) contro lo schema live, ho "fixato" `model_has_roles` a plurale **per falsa simmetria**,
   senza controllarlo — mentre la tabella allora esistente era `model_has_role` (singolare). Config
   temporaneamente disallineata → query su tabella inesistente.

2. **Passo 2 (altro agente, MOLTO più grave)**: invece di correggere la config, ha creato una migrazione
   (`..._rename_model_has_role_to_model_has_roles_table.php`) con `Schema::rename('model_has_role',
   'model_has_roles')` **hardcoded** — modificando lo schema fisico del database per farlo combaciare
   con l'aspettativa del codice, invece di far combaciare il codice (config) con lo schema. Violazione
   diretta della regola "il nome tabella non si scrive mai in migrazione, si ricava dal model/config".

3. **Passo 3 (Claude, stessa classe di errore)**: partendo dallo stesso sintomo (`model_has_roles`
   inesistente), ho scritto una migrazione di consolidamento
   (`create_model_has_roles_table.php`) con `private const TABLE = 'model_has_roles';` passato come
   secondo argomento esplicito a `tableCreate()`/`tableUpdate()` — **stesso errore del Passo 2**,
   solo spostato dentro `Schema::create`/`Schema::table` invece che `Schema::rename`. L'utente ha
   corretto immediatamente: "nelle migrazioni non scriviamo MAI il nome della tabella, dal nome del
   file ricaviamo il modello che ha sia la connessione che il nome della tabella". Il secondo argomento
   opzionale `?string $table` di `tableCreate()`/`tableUpdate()` (in `XotBaseMigration`) esiste per
   casi legittimi (tabelle senza alcun model possibile, es. pivot morph senza classe dedicata — vedi
   `docs/chat/audit-models-migrations-seeders-factories.md` § TimberBilling) — **non** per bypassare
   un `$model_class` che esiste già e sa risolvere il nome tabella da solo. Fix corretto (già applicato,
   vedi sotto): `protected ?string $model_class = ModelHasRole::class;` esplicito, zero argomenti a
   `tableCreate()`/`tableUpdate()`, il nome tabella non compare come stringa letterale da nessuna parte
   nella migrazione.

## Perché il passo 2 è l'errore da non ripetere MAI

`config('permission.table_names.model_has_roles')` è **l'unica fonte di verità** per quale tabella
Spatie deve interrogare — l'utente può cambiarla in qualsiasi momento. Una migrazione non deve **mai**:

- hardcodare un nome tabella letterale (`'model_has_role'`, `'model_has_roles'`, …) in `Schema::create`,
  `Schema::table`, `Schema::rename` — nemmeno per "correggere" un mismatch;
- rinominare/ricreare una tabella fisica solo per farla combaciare con ciò che il codice si aspetta.

Se c'è un mismatch tra config e schema reale, **la config è sbagliata, non lo schema** — a meno che
l'utente non abbia esplicitamente chiesto di rinominare la tabella. Il fix corretto è sempre e solo:
allineare `config/permission.php` (e l'overlay tenant) al nome tabella che esiste *davvero* ora.

## Pattern corretto, applicato ora (verificato)

- **`model_has_roles`** — modello `Modules\User\Models\ModelHasRole`: `getTable()` override che fa
  `return config('permission.table_names.model_has_roles');` ad ogni chiamata — mai
  `protected $table = '...'` hardcoded. Migrazione
  (`Modules/User/database/migrations/2026_07_27_150000_create_model_has_roles_table.php`):
  `protected ?string $model_class = ModelHasRole::class;` + `$this->tableCreate()` senza secondo
  argomento — il nome tabella non compare mai come stringa letterale, arriva dal model che a sua
  volta legge la config.
- **`role_has_permissions`** — modello `Modules\User\Models\RoleHasPermission`: stesso pattern,
  `getTable()` override su `config('permission.table_names.role_has_permissions')`. Causa radice
  distinta ma imparentata: `Illuminate\Database\Eloquent\Relations\Concerns\AsPivot::getTable()`
  (il trait usato da `Pivot`) **non** applica `Str::pluralStudly()` come il `Model::getTable()`
  normale — un `Pivot` senza `$table`/override esplicito finisce quasi sempre col nome sbagliato per
  classi multi-parola (`RoleHasPermission` → `role_has_permission`, non `role_has_permissions`).
  Migrazione (`Modules/User/database/migrations/2023_01_01_000010_create_role_has_permissions_table.php`):
  `protected ?string $model_class = RoleHasPermission::class;` esplicito (prima assente, la
  risoluzione automatica dal filename avrebbe comunque funzionato ma è più fragile — vedi
  `xotbase-migration-religion.md` sul perché preferire esplicito quando il mapping non è banale).
- Conseguenza pratica: le due tabelle fisiche sono state rinominate una sola volta (Passo 2, già
  fatto, dati reali preservati — **non annullare questi rename**, `git forward-only` si applica
  anche allo schema DB) e la config ora punta correttamente ai nomi plurale, coerenti con la realtà
  attuale. Se in futuro serve un altro nome, si cambia SOLO la config — mai una nuova migrazione di
  rename, mai un argomento tabella hardcoded in una migrazione di create/update.

Verificato end-to-end: `$user->roles` restituisce le righe corrette senza errori dopo l'allineamento.

## Regola generale per pivot Spatie/senza-model-dedicato in questo progetto

1. Il modello pivot **sempre** esiste (mai "pivot senza model" — vedi
   `docs/chat/audit-models-migrations-seeders-factories.md`).
2. Se il nome tabella è configurabile da un package esterno (`config('permission.table_names.*')`,
   analoghi di altri package), il modello **deve** avere `getTable()` che legge quella config a ogni
   chiamata — mai `protected $table` statico, mai risolto una volta sola nel costruttore se la config
   può cambiare a runtime (`config()->set(...)`).
3. La migrazione **deve** avere `$model_class` puntato al modello pivot (esplicito se il filename→model
   guessing non è ovvio) e **mai** passare un nome tabella letterale come secondo argomento di
   `tableCreate()`/`tableUpdate()` per "risolvere" un mismatch — quell'argomento esiste solo per il
   caso limite di una tabella senza *alcun* model plausibile (vedi TimberBilling
   `timber_processing_step_processable` nell'audit citato sopra), non come scorciatoia generale.
