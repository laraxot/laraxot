---
title: "Platform Module — Doctrine"
type: doctrine
tags: [platform, coordination, module-doctrine]
created: 2026-09-05
updated: 2026-09-05
qmd: "Platform module doctrine BMAD analysis purpose religion philosophy policy why zen gap enhancements split merge"
related:
  - "../../Xot/docs/module.md"
  - "../../Tenant/docs/module.md"
---

# Platform Module — Doctrine

## Scope (Scopo)

Platform è il livello di coordinamento tra i moduli. Gestisce le feature flags (AccountFeatures), il persistence delle impostazioni (SettingsRepository), gli eventi cross-modulo, e i job di background che orchestrano il flusso tra i vari moduli. Non è un modulo di dominio: è un modulo di infra-struttura applicativa.

## Religion (Religione)

**"Chiedi i permessi una volta, proteggi ovunque. Persiste un'impostazione una volta, usala ovunque."** Platform è il guardiano delle convenzioni: ogni modulo deve chiedere autorizzazione tramite AccountFeatures, mai hardcoded. Platform non fa nulla di dominio — solo coordinamento.

## Philosophy (Filosofia)

- **Feature flags centrati sull'account**: ogni feature è legata a un account, non a un utente singolo
- **Settings come SSOt**: un'impostazione scritta una volta è leggibile da tutti i moduli
- **Event-driven architecture**: i moduli comunicano tramite eventi, mai chiamate dirette
- **Job queue per tutto ciò che è async**: mai bloccare la request cycle

## Policy (Politica)

- Mai usare Service Locator: ogni dipendenza deve essere iniettata
- AccountFeatures: le feature sono abilitate per account, non per utente
- SettingsRepository: le impostazioni sono versionate e auditate
- Eventi: ogni evento deve avere un handler dedicato, mai logica inline
- Job: ogni job deve essere idempotente e retryabile

## Why (Perché)

Platform esiste perché senza un layer di coordinamento, ogni modulo implementerebbe le proprie feature flags, settings, e eventi in modo incompatibile. Platform garantisce che la stessa impostazione sia condivisa e coerente su tutto il monorepo.

## Zen

*"Condividi ciò che è comune, mai duplicare. Chiedi, non assumere. Instrada, non redirezionare."*

## Gap

- `Services/AuditLogger.php` viola la propria regola "Actions not Services" → da spostare in Actions
- ARCHITECTURE.md mancante nella root del modulo
- Test coverage minimo (10 test totali)

## Add

- Spostare AuditLogger in una Queueable Action
- Scrivere ARCHITECTURE.md con diagramma delle dipendenze
- Aggiungere test feature per eventi cross-modulo
- Dashboard di monitoring per feature flags e settings

## Split/Merge

**Mantenere come-is.** Platform ha un ruolo unico di coordinamento che non si sovrappone ad altri moduli. Non ha logica di dominio propria, quindi non ha senso spezzarlo. Non va fuso con Xot perché Platform è specifico per l'orchestrazione applicativa, mentre Xot è l'infra-struttura di base.

## Future Enhancements

1. **Feature flag rollout**: percentuale di utenti per feature (canary deployment)
2. **Settings versioning**: tracciare ogni cambio di settings con chi, quando, perché
3. **Event replay**: capability di riprodurre eventi passati per debugging
4. **Cross-module transaction**: supporto per operazioni atomiche che coinvolgono più moduli
5. **Platform API**: endpoint REST per query di feature flags e settings da client esterni
6. **Audit trail UI**: pannello Filament per visualizzare e filtrare audit log
