---
title: "Filament 5.x compatibility - modulo Notify"
type: concept
tags: [filament, compatibility]
created: 2026-07-14
updated: 2026-07-14
qmd: "filament-5x-compatibility filament 5.x compatibility - modulo notify"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index-2.md"
  - "./00-index.md"
  - "./absolute-completion-100.md"
  - "./acronym-naming-conventions-1.md"
  - "./acronym-naming-conventions-2.md"
  - "./acronym-naming-conventions.md"
  - "./action-plan-immediate.md"
---

# Filament 5.x compatibility - modulo Notify

**Versione Filament:** v5.2.1

## Stato compatibilità

Il modulo Notify è **compatibile** con Filament 5.x. Nessun breaking change funzionale rispetto a Filament 4.x.

## Note specifiche modulo

- Il fix precedente (Filament 4.x) per `SpatieEmail` — uso di `update()` al posto di `increment()` — resta valido
- Le email stagionali tramite layout Blade non sono impattate dall'upgrade Filament

## Regole architetturali

Tutte le classi Filament **devono** estendere le classi `XotBase*`:

| Tipo | Classe base |
|------|------------|
| Resource | `XotBaseResource` |
| ListRecords | `XotBaseListRecords` |
| CreateRecord | `XotBaseCreateRecord` |
| EditRecord | `XotBaseEditRecord` |
| ViewRecord | `XotBaseViewRecord` |
| Widget | `XotBaseWidget` |
## Inclusione nei Blade View

Per includere un widget Filament (che è un componente Livewire) all'interno di una vista Blade o di una pagina Folio, **non** usare la sintassi del tag `<livewire:module::widget-name />` a meno che non sia esplicitamente registrato. 

La sintassi corretta e sicura per Filament 5.x è l'uso diretto della classe:

```blade
@livewire(\Modules\User\Filament\Widgets\Auth\LoginWidget::class)
```

Questo evita errori di `ComponentNotFoundException` nelle architetture modulari.


## Checklist modulo

- [x] Nessun import diretto da `Filament\*` base classes
- [x] Fix `SpatieEmail` (da Filament 4.x) confermato compatibile
- [ ] Verificare compatibilità con Livewire 4.x dopo upgrade
- [ ] Verificare Tailwind CSS 4.1+ dopo upgrade

## Riferimenti

- [Guida upgrade Filament 5 (Xot)](../../Xot/docs/filament-5-upgrade-guide.md)
- [Regole Laraxot per Filament 5 (Xot)](../../Xot/docs/filament-5-laraxot-rules.md)
- [Documentazione ufficiale](https://filamentphp.com/docs/5.x/upgrade-guide)
