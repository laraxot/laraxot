---
title: "Regole di Dipendenza — Modulo UI"
type: rule
tags: [dependency, rules]
created: 2026-07-14
updated: 2026-07-14
qmd: "dependency-rules regole di dipendenza — modulo ui"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./04-datas.md"
  - "./advanced-form-components-1.md"
  - "./advanced-form-components.md"
  - "./agent-confidence-discipline.md"
  - "./agent-confidence-protocol.md"
  - "./agent-edit-discipline.md"
---

# Regole di Dipendenza — Modulo UI

> **Creato**: 2026-07-06
> **Stato**: ATTIVO — regola vincolante

---

## Principio Fondamentale

Il modulo **UI è una dipendenza condivisa** di tutti gli altri moduli. Il suo scopo è fornire componenti UI generici (Filament, Blade, Livewire) senza conoscere il dominio applicativo di nessun modulo specifico.

```
Xot ← UI ← (tutti gli altri moduli: Geo, User, Tenant, Activity, ecc.)
```

**La freccia indica "dipende da".**

---

## Regola: UI NON dipende da moduli domain-specific

| Modulo        | Può dipendere da UI? | UI può dipendere da? |
|---------------|----------------------|----------------------|
| Geo           | ✅ Sì                | ❌ No                 |
| User          | ✅ Sì                | ✅ Sì (solo per UserData) |
| Tenant        | ✅ Sì                | ✅ Sì (multi-tenancy) |
| Activity      | ✅ Sì                | ❌ No                 |
| Xot           | ✅ Sì                | ✅ Sì (base classes)  |
| Media         | ✅ Sì                | ❌ No                 |

---

## Componenti Geo: dove devono stare

I componenti che richiedono funzionalità geografiche **appartengono al modulo Geo**, non al modulo UI.

### Componenti disabilitati in UI (rinominati `.old`)

| File                                                                        | Motivo                                          | Dove va            |
|-----------------------------------------------------------------------------|-------------------------------------------------|--------------------|
| `app/Livewire/Components/Map/InteractiveMap.php.old`                        | Dipende da dati geografici (Geo module)         | `Modules/Geo/`     |
| `app/Filament/Forms/Components/LocationSelector.php.old`                    | Usa `Modules\Geo\Models\Comune` direttamente    | `Modules/Geo/`     |
| `resources/views/livewire/components/map/interactive-map.blade.php.old`     | View del componente Geo disabilitato            | `Modules/Geo/`     |

### Contratti e Null Services (accettabili in UI)

I seguenti file sono **accettabili** nel modulo UI perché definiscono interfacce astratte senza dipendere da classi Geo concrete:

| File                                            | Motivo                                                    |
|-------------------------------------------------|-----------------------------------------------------------|
| `app/Contracts/GeocodingServiceContract.php`    | Interfaccia astratta — nessuna dipendenza da Geo          |
| `app/Contracts/MapServiceContract.php`          | Interfaccia astratta — nessuna dipendenza da Geo          |
| `app/Services/Map/NullGeocodingService.php`     | Null Object pattern — fallback quando Geo non è installato |
| `app/Services/Map/NullMapService.php`           | Null Object pattern — fallback quando Geo non è installato |

### Documentazione archiviata

| File                                        | Motivo                              |
|---------------------------------------------|-------------------------------------|
| `docs/map-integration-guide.md.old`         | Descriveva componenti Geo nel UI    |

---

## LocationSelector: già disabilitato

`LocationSelector` importava direttamente `Modules\Geo\Models\Comune` — violazione della regola.
È stato rinominato `LocationSelector.php.old` in data 2026-07-06.

Se in futuro si vuole un selettore regione/provincia/CAP nel modulo UI, deve usare **solo contratti astratti** (es. `GeocodingServiceContract`) e ricevere i dati via dependency injection, senza importare classi concrete di Geo.

---

## Come aggiungere componenti mappa nel progetto

1. Il modulo Geo crea il componente (es. `Modules\Geo\Livewire\InteractiveMap`)
2. Il modulo Geo può usare componenti UI generici (form fields, layout, ecc.)
3. Il modulo Geo registra il suo service provider che carica i componenti Livewire
4. Le viste del tema usano `<livewire:geo::components.map.interactive-map />`

---

## Violazioni da correggere

Cercare periodicamente:

```bash
grep -r "Modules\\\\Geo" laravel/Modules/UI/app/
grep -r "use Modules\\\\Geo" laravel/Modules/UI/
```

Nessun risultato = modulo UI pulito.
