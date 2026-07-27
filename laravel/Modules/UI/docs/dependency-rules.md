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

### Contratti / Adapter Map-Location — **non** accettabili in UI

Rimossi il 2026-07-22 (vedi [geo-boundary.md](./geo-boundary.md)): anche i contratti/null-adapter erano dominio geografico.

| Rimosso | Motivo |
|---------|--------|
| `app/Adapters/Location/`, `app/Adapters/Map/` | Dominio Geo, non design system |
| `LocationDataProviderContract`, `MapServiceContract`, `GeocodingServiceContract` | Stesso dominio |
| `LocationSelector.php` attivo | Selettore geografico |

Se serve geografia: modulo `Geo` (quando presente), mai ricopiare in UI.

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
