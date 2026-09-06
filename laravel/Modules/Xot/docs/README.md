---
title: "Xot — La Base Sacra di Laraxot"
description: "Modulo fondamentale che fornisce funzionalità core e strutture per tutti gli altri moduli"
module: "Xot"
alias: "xot"
version: "1.0.0"
priority: 2
active: true
status: "core-foundation"
author: "Team Laraxot"
license: "Proprietary"
php_version: "^8.1"
core_version: "10.0"
dependencies: ["User", "Tenant"]
extends: []
extended_by: 46
documentation_date: "2026-05-27"
---

# Xot — La Base Sacra di Laraxot

## Scopo

Xot è il fondamento su cui poggiano tutti i 46 moduli della famiglia Laraxot. Non è un modulo come gli altri: è il **contratto fondamentale** che garantisce che un singolo cambiamento a `XotBaseResource` propaghi i suoi effetti su ogni modulo del sistema. Xot fornisce le classi base, le convenzioni, le utility e il pattern architetturale che rendono possibile la manutenibilità a scala di un ecosistema modulare di questa dimensione.

## Religione

Xot è trattato come un **sacro vincolo** non negoziabile. Le regole che impone non sono suggerimenti: sono i giuramenti che legano l'ecosistema. La religione di Xot si esprime in sette non-negoziabili:

### 1. "Never Filament Directly"

**Regola**: Filament deve essere sempre esteso attraverso Xot.
**Perché**: i suoi i 
- Resource → `XotBaseResource`
- Page → `XotBasePage`
- Widget → `XotBaseWidget` (e varianti: `XotBaseChartWidget`, `XotBaseStatsOverviewWidget`, `XotBaseTableWidget`)
- Action → `XotBaseAction`
- List/Create/Edit/View → `XotBaseListRecords`, `XotBaseCreateRecord`, `XotBaseEditRecord`, `XotBaseViewRecord`
- PanelProvider → `XotBasePanelProvider`
- Dashboard → `XotBaseDashboard`
- Login/Register → `XotBaseLogin`, `XotBaseRegister`

**Implementazione**:
```php
class MyResource extends XotBaseResource
{
    public static $model = MyModel::class;
    // ...
}
```

### 2. "Actions, Not Services"

**Regola**: la logica di business vive in `Actions` con metodo `execute()` e `use QueueableAction`.
**Perché**: standard location, metodo standard, queueable di default. Prevedibilità across moduli.

**Giusto**:
```php
namespace Modules\{Modulo}\Actions;

class MyAction
{
    use QueueableAction;

    public function execute(...): mixed
    {
        // business logic qui
    }
}
```

**Mai**:
```php
namespace app/Services; // ❌ vietato
class SomethingService {
    // business logic qui
}
```

### 3. `phpstan.neon` is Sacred

**Regola**: il file `phpstan.neon` **è modificato** da nessun modulo. No `ignoreErrors`, no module-specific overrides. Gli errori si correggono nel codice, mai silenziati.

**Perché**: la static analysis è la fede. Ignorare errori difende il punto.

### 4. "Folio e Volt per il pubblico, Filament per l'admin"

**Regola**: separazione netta: pagine pubbliche in `resources/views/pages/` (Folio), pagine admin in `Modules/{Mod}/app/Filament/`.

**Perché**: Folio è file-based e semplice per public sites. Filament è declarative e reusable per admin.

### 5. "Traduzioni dai file di lingua"

**Regola**: mai hardcoded labels:
```php
// SBAGLIATO
$input->label('User Email');

// GIUSTO
->label(trans('user::user.email'))  // o
->label(trans_string('user.email'))
```

**Perché**: translations are centralized, maintainable, and reusable. A label change updates everywhere.

### 6. "Base Classes for Everything"

**Regola**: ogni classe eredita da una XotBase: `XotBaseModel`, `XotBaseResource`, `XotBasePage`, `XotBasePanelProvider`, `XotBaseMigration`, `XotBaseServiceProvider`.

**Perché**: Consistency. Shared features (created_by, updated_by, auto-discovery) are inherited, not reimplemented.

### 7. "La cartella docs è la memoria"

**Regola**: la `docs/` cartella è la fonte di verità per:
- Architecture decisions
- Conventions
- Examples
- Integration points

**Aggiorna `docs/` first.** Code follows documentation, not the reverse.

**Perché**: Future maintainers (and future you) should read before writing code. Undocumented code is technical debt.

## Filosofia

> **"DRY e KISS portati alla loro conclusione logica."**

- Un posto per aggiornare = un solo framework change
- Standard naming = leggibilità immediata
- Declared constraints = safer refactoring
- Consistency = no surprises

La filosofia di Xot è l'**astrazione come servizio**. Le classi base non sono wrapper pigri: sono interfacce stabilizzanti che intercettano i cambiamenti dell'ecosistema Filament/Laravel prima che raggiungano i moduli. Xot crede che la complessità si gestisca non combattendola ma **interponendosi** tra essa e chi la usa.

Il sistema è progettato per l'**ereditarietà composita**: ogni modulo eredita struttura, convenzioni e pattern da Xot, ma è libero di estendere e specializzare. Il campo `type` nella tabella users (`Parental\HasChildren`) è l'incarnazione di questa filosofia: una singola tabella, molteplici forme identitarie.

## Politica

- **Gerarchia dei moduli**: Xot sta sopra tutti. Ogni dipendenza transitiva passa per Xot o è esplicitamente dichiarata in `module.json`.
- **Nessun fork**: i moduli non forkano Xot. Lo estendono. Questo garantisce che un fix in Xot sia istantaneamente disponibile everywhere.
- **PHPStan Level 10 non negoziabile**: la rigidità tipativa è politica di qualità, non preference tecnica.
- **Comunità e contributi**: il `docs/` è la fonte della verità. Un PR senza documentazione è un rifiuto implicito.
- **Versionamento**: `minimumCoreVersion: "10.0"` significa che Xot traccia la versione del core Laravel, non la versione del modulo.

## Zen

> **"Il token scade, il log resta, l'utente è sempre lo stesso id."**

Lo Zen di Xot è la **prevedibilità assoluta**. Uno sviluppatore che apre un modulo sconosciuto trova sempre:
- La stessa struttura di cartelle
- Gli stessi pattern di naming
- Le stesse classi base
- Lo stesso flusso di azioni

Questa familiarità è il dono di Xot. Non è un framework: è un **linguaggio** che tutti i moduli parlano fluentemente.

## Perché esiste

Ogni ecosistema modulare di grandi dimensioni affronta il problema della **frammentazione**: i moduli divergono nei pattern, nelle convenzioni e nelle astrazioni, rendendo il sistema inmaneggiabile. Xot esiste per eliminare questo problema alla radice, imponendo un singolo contratto che tutti devono rispettare. La sua esistenza è la risposta al costo marginale di manutenzione che cresce con il numero di moduli.

## Cosa Mancherebbe (Gap Analysis)

| Gap | Severità | Suggerimento |
|-----|----------|--------------|
| Nessun modulo di osservabilità nativo (metrics, tracing, distributed tracing) | Alta | Creare un modulo `Observability` che estenda Xot con dashboard, alerting e distributed tracing |
| Manca un modulo di event sourcing/decision log | Alta | Aggiungere `EventSourcing` come sottodominio di Xot o modulo dedicato |
| Nessun modulo di feature flags | Media | Creare `FeatureFlags` basato su `Extra` model con toggle runtime |
| Assenza di modulo di audit trail centralizzato | Media | Estendere `Activity` o creare `AuditTrail` come Xot submodule |
| Nessun pattern CQRS esplicito | Media | Documentare se `Actions` coprono il pattern CQRS o se serve un modulo `Cqrs` |
| Nessun modulo per task scheduling avanzato | Bassa | Creare `Scheduler` con cron visuale e dashboard |
| Manca documentazione `docs/archived/project-religion-politics-zen.md` referenziata | Bassa | Verificare esistenza o creare questo file storico |

## Proposte di Split/Merge (Solo Documentazione)

### Split Consigliati

1. **Xot → XotCore + XotFilament** — Separare le astrazioni core (model, migration, service provider) dalle astrazioni Filament (resource, page, widget, panel provider). Motivo: ridurre il coupling tra il layer di persistenza e il layer di presentazione admin. Se Filament cambia versione major, solo `XotFilament` deve essere aggiornato.

2. **Xot → XotCore + XotActions** — Estrazione del sistema di Actions in un modulo separato per renderlo riutilizzabile indipendentemente dal framework base.

### Merge Consigliati

1. **Activity + AuditTrail** — Se `Activity` traccia solo azioni utente e `AuditTrail` traccia cambiamenti dati, creare un modulo `Audit` con una visione unificata.

2. **Job + Notify + Email** — Se `Job` gestisce code, `Notify` notifiche e `Email` invio email, fondere in `Communications` con un layer di astrazione comune (message bus).

---

*Documento generato secondo le convenzioni del progetto — modulo `Xot` — data 2026-05-27*
