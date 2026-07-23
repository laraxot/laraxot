# Module Directory Structure Rule

> **Regola**: Le cartelle dei moduli devono seguire la struttura standard Laravel/Packt.

**Date Established**: 2026-03-13  
**Status**: Active  
**Priority**: Critical  

---

## La Regola

**Tutti i file di codice sorgente devono stare in `app/`, MAI nella root del modulo.**

### Struttura Corretta

```
laravel/Modules/{ModuleName}/
├── app/
│   ├── Actions/
│   ├── Datas/          ✅ Data Objects qui
│   ├── Filament/
│   ├── Helpers/
│   ├── Http/
│   ├── Models/
│   ├── Providers/
│   ├── Rules/
│   ├── Services/
│   └── Traits/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── docs/
├── lang/
├── resources/
│   ├── views/
│   └── assets/
├── routes/
├── tests/
│   ├── Feature/
│   └── Unit/
├── composer.json
└── module.json
```

### Struttura SBAGLIATA ❌

```
laravel/Modules/{ModuleName}/
├── Actions/            ❌ Mai nella root
├── Datas/              ❌ Mai nella root
├── Models/             ❌ Mai nella root
├── Services/           ❌ Mai nella root
├── app/                ✅ Solo app/ è corretto
└── ...
```

---

## Esempi Specifici

### ✅ Corretto: Xot Module

```
laravel/Modules/Xot/
├── app/
│   ├── Actions/
│   ├── Datas/              ✅ XotData.php qui
│   ├── Filament/
│   ├── Models/
│   ├── Providers/
│   └── Services/
├── config/
├── database/
├── docs/
└── module.json
```

### ❌ Sbagliato: Xot Module (Pre-Cleanup)

```
laravel/Modules/Xot/
├── Datas/                  ❌ XotData.php stub qui (RIMOSSO)
├── app/
│   └── Datas/              ✅ XotData.php reale qui
└── ...
```

---

## Rationale

### 1. Standard Laravel/Packt

I moduli Laravel seguono la convenzione di Packt/`nwidart/laravel-modules`:
- Tutto il codice PHP va in `app/`
- La root del modulo contiene solo configurazione e metadata

### 2. Autoloading Coerente

Il composer.json del modulo configura l'autoloading su `app/`:

```json
{
    "autoload": {
        "psr-4": {
            "Modules\\ModuleName\\": "app/"
        }
    }
}
```

File nella root del modulo non sono autoloadati correttamente.

### 3. Consistenza

Tutti i moduli devono avere la stessa struttura per:
- Facilità di navigazione
- Strumenti di analisi statica (PHPStan, Psalm)
- IDE autocomplete e refactoring

### 4. Separazione Chiara

- `app/` = Codice sorgente (classes, traits, interfaces)
- `config/` = Configurazione
- `database/` = Migrazioni, factories, seeders
- `resources/` = Views, assets
- `docs/` = Documentazione

---

## PHPStan Memory Management

Per analisi di grandi dimensioni (es. `Modules/`), utilizzare sempre il flag della memoria a livello di interprete PHP per evitare crash dei parallel workers:

```bash
php -d memory_limit=-1 ./vendor/bin/phpstan analyse [target] --memory-limit=-1
```

---

## Violazioni Comuni

### 1. Datas nella Root

**Violazione**: `Modules/Xot/Datas/XotData.php`

**Fix**: Spostare in `Modules/Xot/app/Datas/XotData.php`

**Stato**: ✅ Risolto (2026-03-13)

### 2. Filament nella Root

**Violazione**: `Modules/Xot/Filament/Forms/Components/XotBasePlaceholder.php`

**Fix**: Spostare in `Modules/Xot/app/Filament/Forms/Components/XotBasePlaceholder.php`

**Stato**: ✅ Risolto (2026-03-13) - cartella `Filament/` rimossa

### 3. Services nella Root

**Violazione**: `Modules/Xot/Services/ArrayService.php`

**Fix**: Spostare in `Modules/Xot/app/Services/ArrayService.php`

**Stato**: ✅ Risolto (2026-03-13) - cartella `Services/` rimossa

### 4. Helpers nella Root

**Violazione**: `Modules/Xot/Helpers/Helper.php` o `Modules/Xot/helpers/Helper.php`

**Fix**: Se sono helper moderni, spostare in `Modules/Xot/app/Helpers/`. Se sono legacy, rimuovere.

**Stato**: ✅ Risolto (2026-03-13) - entrambe le cartelle `Helpers/` e `helpers/` rimosse

### 5. Actions/Application/Events/Listeners/Database nella Root

**Violazione**: `Modules/{Module}/Actions/`, `Application/`, `Events/`, `Listeners/`, `Database/` (PascalCase)

**Fix**:

| Root (vietato) | Destinazione |
| :--- | :--- |
| `Actions/` | `app/Actions/` |
| `Application/` | `app/Application/` |
| `Events/` | `app/Events/` |
| `Listeners/` | `app/Listeners/` |
| `Database/` | `database/` (minuscolo) |

**Stato**: ✅ `Modules/User/` bonificato (2026-06-18). Tutti i moduli conformi.

---

## Cleanup Eseguito

### 2026-03-13: Xot Module Root Cleanup

**Prima**:
```
laravel/Modules/Xot/
├── Datas/                  ❌ Cartella legacy nella root
│   └── XotData.php         (stub vuoto)
├── Filament/               ❌ Cartella legacy nella root
│   └── Forms/Components/
│       └── XotBasePlaceholder.php  (duplicato)
├── Services/               ❌ Cartella legacy nella root
│   └── ArrayService.php    (file singolo)
├── Helpers/                ❌ Cartella legacy nella root
│   ├── Helper.php          (legacy)
│   └── PathHelper.php      (legacy)
├── helpers/                ❌ Cartella legacy nella root (lowercase)
│   ├── Helper.php          (legacy)
│   └── PathHelper.php      (legacy)
└── app/
    ├── Datas/              ✅ XotData.php qui
    ├── Filament/           ✅ Tutti i componenti Filament qui
    ├── Services/           ✅ ArrayService.php qui
    └── ...
```

**Dopo**:
```
laravel/Modules/Xot/
├── app/
│   ├── Datas/              ✅ XotData.php qui
│   ├── Filament/           ✅ Tutti i componenti Filament qui
│   ├── Services/           ✅ ArrayService.php qui
│   └── ...
└── (root pulita - solo config, docs, lang, resources, routes, tests)
```

**Elementi Rimossi**:
- `laravel/Modules/Xot/Datas/` (cartella intera)
- `laravel/Modules/Xot/Filament/` (cartella intera)
- `laravel/Modules/Xot/Services/` (cartella intera)
- `laravel/Modules/Xot/Helpers/` (cartella intera)
- `laravel/Modules/Xot/helpers/` (cartella intera)

---

## Verifica

### Comando di Verifica

```bash
# Trova cartelle "app-like" nella root dei moduli
find laravel/Modules -maxdepth 2 -type d \( \
    -name "Actions" -o \
    -name "Application" -o \
    -name "Database" -o \
    -name "Events" -o \
    -name "Listeners" -o \
    -name "Datas" -o \
    -name "Filament" -o \
    -name "Helpers" -o \
    -name "helpers" -o \
    -name "Models" -o \
    -name "Providers" -o \
    -name "Services" \
\) ! -path "*/app/*" ! -path "*/tests/*" ! -path "*/database/*"
```

**Output atteso**: (vuoto - nessuna violazione)

### Checklist per Nuovi Moduli

Quando crei un nuovo modulo:

- [ ] Tutte le classi in `app/`
- [ ] Nessuna cartella di codice nella root
- [ ] `composer.json` configurato correttamente
- [ ] `module.json` nella root

---

## Eccezioni

### Nessuna Eccezione

Non ci sono eccezioni a questa regola. Tutto il codice sorgente DEVE stare in `app/`.

### Cartelle NON Consentite nella Root

Queste cartelle **NON** devono mai stare nella root del modulo:

- `Actions/` → deve essere `app/Actions/`
- `Application/` → deve essere `app/Application/`
- `Database/` → deve essere `database/` (minuscolo; mai PascalCase)
- `Events/` → deve essere `app/Events/`
- `Listeners/` → deve essere `app/Listeners/`
- `Datas/` → deve essere `app/Datas/`
- `Filament/` → deve essere `app/Filament/`
- `Helpers/` o `helpers/` → deve essere `app/Helpers/` o rimosso se legacy
- `Models/` → deve essere `app/Models/`
- `Providers/` → deve essere `app/Providers/`
- `Services/` → deve essere `app/Services/`
- Qualsiasi altra cartella contenente codice PHP sorgente

### File Consentiti nella Root

Solo questi file sono permessi nella root del modulo:

- `composer.json` - Dipendenze PHP
- `module.json` - Metadata del modulo
- `package.json` - Dipendenze JavaScript (opzionale)
- `README.md` - Documentazione base
- `CHANGELOG.md` - Storico cambiamenti
- `.editorconfig` - Configurazione editor
- `.gitignore` - Git ignore rules
- `*.code-workspace` - VSCode workspace (UNO solo)

### File NON Consentiti nella Root (Backup/Copy)

I seguenti file **NON** devono mai stare nella root del modulo (o in qualsiasi cartella):

- `* copy.*` - File di copia (es. `.gitattributes copy`, `.gitconfig copy`)
- `*.copy` - File con estensione .copy
- `*.bak`, `*.backup` - File di backup
- `*.old`, `*.old1`, `*.old2` - File vecchi
- `*_old` - File con suffisso _old

Questi file devono essere:
1. Aggiunti al `.gitignore` (root e modulo)
2. Eliminati dal filesystem

**Stato**: ✅ Pattern `*.backup` / `*.backup.*` in root, `laravel/`, ogni modulo/tema e `docs/.gitignore` (2026-05-21). Vedi anche [gitignore backup files](../../../../docs/wiki/memories/gitignore-backup-files.md).

---

## Documentazione Correlata

- [Workspace File Naming Rule](workspace-file-rule.md)
- [Module Structure Standards](module-structure.md)
- [Coding Standards](best-practices.md)
- [Architecture Guide](architecture/structure.md)

---

## Riferimenti

- [Laravel Package Structure](https://laravel.com/docs/packages)
- [Packt/Laravel Modules](https://packtmodules.com/)
- [PSR-4 Autoloading](https://www.php-fig.org/psr/psr-4/)

---

## Regressione e ri-bonifica 2026-07-06

Le cartelle root PascalCase erano tornate (probabile merge/copy incidentale
di un agente in una sessione precedente): `Modules/User/{Listeners,
Application,Events,Actions,Database}`, `Modules/Notify/Models`,
`Modules/Cms/Actions`, oltre a `Modules/Xot/{Datas,Filament,Providers,_docs,
claude-code-bmad-skills}` (queste ultime rimosse da un altro agente in
parallelo nella stessa sessione). Verificato per ciascuna, prima di
cancellare, che **non** fossero l'unica copia raggiungibile (autoload
psr-4 di ogni modulo mappa `Modules\X\` → `app/` — root non è mai
autoloadato) e che nessun codice di produzione le importasse per path
diretto. `Modules/User/Database/Migrations/` non era caricata da
`XotBaseServiceProvider::boot()` (usa `database/migrations` minuscolo
hardcoded) — mai eseguita, sicura da rimuovere anche se il contenuto
differiva dalle migration reali in `database/migrations/`.

**Eccezione confermata**: `Modules/Xot/helpers/Helper.php` (lowercase)
resta alla root — richiesto esplicitamente da `laravel/phpstan.neon`
(`scanFiles`, di proprietà esclusiva dell'utente). Non spostare né
rimuovere senza che sia l'utente a modificare prima `phpstan.neon`.

Rimossi anche file spazzatura non previsti dalla checklist originale:
`Modules/Xot/.gitattributes copy`, `Modules/Xot/_activity.code-workspace`
(duplicato byte-identico di `_xot.code-workspace`, la regola ammette un solo
`*.code-workspace`), `Modules/Activity/.md` (file vuoto), due varianti
duplicate di `.docs-directory-violation-reminder.md` in `Modules/Cms`
(contaminazione da un progetto diverso, `base_saluteora`, non pertinente a
questo repo).

**Conflitto aperto, non risolto unilateralmente**: la lista "File Consentiti
nella Root" più sopra include esplicitamente `CHANGELOG.md`, ma un'istruzione
successiva dell'utente (2026-07-06) dice "come file .md alla root deve
esserci solo README.md". `CHANGELOG.md` esiste ancora alla root di
`Activity`, `AI`, `Gdpr` — non cancellato in attesa di chiarimento esplicito
dell'utente, per non perdere contenuto storico senza conferma.

*Ultimo aggiornamento: 2026-07-06*
