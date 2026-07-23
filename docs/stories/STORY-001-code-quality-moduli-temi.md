---
title: "STORY-001 — Code Quality per moduli e temi"
type: story
status: defined
tags: [bmad, code-quality, modules, themes, phpstan, pest]
created: 2026-07-17
updated: 2026-07-17
qmd: "BMAD story code quality report moduli temi PHPStan Pest Laraxot"
story: STORY-001
issues:
  - "https://github.com/laraxot/base_techplanner_fila5/issues/46"
discussions:
  - "https://github.com/laraxot/base_techplanner_fila5/discussions/47"
---

# STORY-001 — Code Quality per moduli e temi

**Epic:** Engineering Excellence
**Priorità:** Must Have
**Story Points:** 8
**Stato:** Defined
**Assegnatario:** Non assegnato
**Sprint:** Backlog corrente

## User Story

Come maintainer della piattaforma TechPlanner,
voglio una baseline di qualità misurabile e un piano verificabile per ogni modulo e tema,
così da ridurre regressioni e debito tecnico senza introdurre refactoring speculativi.

## Contesto

La codebase è distribuita tra 20 moduli applicativi e 6 temi con dimensioni, test e maturità differenti. La documentazione storica non usa sempre gli stessi indicatori e può confondere un conteggio statico con una diagnosi definitiva. Questa story introduce un formato uniforme, vicino al codice owner e ripetibile.

## Scope

### Incluso

- baseline statica per ciascun componente in Modules/*/docs e Themes/*/docs;
- conteggi di file PHP, test, candidati senza strict types, marker di debito, controller, Service/Support ed estensioni Filament sospette;
- priorità P0–P3, criteri di uscita e comandi PHPStan/Pest scoped;
- tracciamento GitHub e limiti espliciti delle metriche.

### Escluso

- modifica del codice applicativo;
- esecuzione o correzione massiva di PHPStan/Pest;
- introduzione di coverage, mutation testing o nuove dipendenze;
- normalizzazione di tutta la documentazione legacy;
- commit, push o merge.

## Flusso operativo

1. Il maintainer apre il report del componente interessato.
2. Verifica i conteggi contro il working tree corrente.
3. Esegue PHPStan L10 e Pest solo sul componente.
4. Classifica i problemi reali e sceglie il primo intervento P0/P1.
5. Corregge la causa condivisa con il minimo diff e un test di regressione.
6. Aggiorna report, issue e discussion con comando ed esito.

## Baseline trasversale

| Area | Evidenza iniziale | Decisione |
|---|---|---|
| Test assenti | Blog, TestModule, Barthelemy, Meetup, Two, Zero | prima identificare se contengono logica eseguibile; test solo sui comportamenti reali |
| Rapporto test/PHP basso | Rating, TechPlanner, Media, Employee, Job, UI | proteggere prima persistenza, autorizzazioni e business rule |
| Controller da classificare | Cms, Employee, Media, Notify, UI, User, Xot, Sixteen | distinguere FO da BO/API; rimuovere i controller FO |
| Marker di debito elevati | soprattutto User e Geo | triage per rischio e rimuovere marker senza azione concreta |
| Estensioni Filament sospette | Xot nella scansione iniziale | verifica manuale contro la gerarchia XotBase prima di modificare |
| Strict types | numerosi candidati trasversali | adozione incrementale nei file toccati, non rewrite massivo |

I dati dettagliati e i comandi sono nei 26 report owner denominati code-quality-improvement-report.md.

## Acceptance Criteria

- [x] Ogni modulo applicativo ha un report nella propria cartella docs.
- [x] Ogni tema ha un report nella propria cartella docs.
- [x] Ogni report distingue metriche statiche, rischi, piano, criteri di uscita e limiti.
- [x] Ogni report contiene comandi PHPStan e Pest scoped al componente.
- [x] La story collega almeno una issue e una discussion GitHub pertinenti.
- [ ] I report prioritari sono validati con esecuzioni reali PHPStan L10 e Pest.
- [ ] Ogni errore confermato genera una story o issue owner piccola e testabile.
- [ ] Le correzioni non modificano phpstan.neon per occultare problemi.
- [ ] Nessun intervento introduce controller FO, estensioni Filament dirette o business logic in Services/Support.

## Note tecniche

### Componenti

- laravel/Modules/*
- laravel/Themes/*
- configurazione PHPStan centrale e test Pest esistenti, solo in lettura in questa story

### Regole architetturali

- PHPStan livello 10 per modulo, senza analisi globale come primo passo;
- Pest con ambiente testing e senza RefreshDatabase;
- XotBase/LangBase al posto di estensioni Filament dirette;
- Folio + Volt per il front office, senza controller;
- Actions con QueueableAction per business logic condivisa;
- tipi concreti e nessun nuovo mixed.

### Sicurezza e dati

- nessuna migrazione o comando distruttivo;
- nessun test autorizza reset del database;
- autenticazione, autorizzazione e scritture persistenti hanno precedenza nella copertura.

## Strategia di test

Per ogni componente prioritario:

1. eseguire PHPStan L10 scoped;
2. eseguire Pest scoped;
3. scegliere un solo problema ad alto rischio;
4. aggiungere il più piccolo test di regressione che lo dimostra;
5. correggere la causa comune;
6. rieseguire entrambi i comandi e registrare l'esito.

## Dipendenze

- vendor Laravel installato e coerente;
- .env.testing valido;
- regole Laraxot/XotBase e configurazione PHPStan centrale disponibili;
- issue owner aggiuntive quando si passa dalla baseline alla modifica di un singolo repository componente.

## Definition of Done

- [x] Story BMAD definita e tracciata.
- [x] Baseline documentale distribuita nei componenti.
- [x] Nessuna modifica al codice applicativo.
- [x] Nessuna nuova dipendenza o astrazione.
- [x] Validazione frontmatter e link completata.
- [x] QMD aggiornato.
- [x] Commento conclusivo pubblicato sull'issue.

## Stima

| Attività | Punti |
|---|---:|
| Censimento e baseline | 2 |
| Report per componente | 3 |
| Story, criteri e tracciamento | 2 |
| Verifica documentale | 1 |
| Totale | **8** |

La remediation del codice non è inclusa: una singola story che correggesse tutti i componenti supererebbe 13 punti e violerebbe INVEST.

## GitHub (tracciamento)

| Tipo | Repository | URL |
|---|---|---|
| Issue | laraxot/base_techplanner_fila5 | https://github.com/laraxot/base_techplanner_fila5/issues/46 |
| **Discussion (canonica)** | laraxot/base_techplanner_fila5 | https://github.com/laraxot/base_techplanner_fila5/discussions/47 |

La discussion root è il coordinamento cross-boundary. Le future remediation devono aprire o riusare issue nella repository owner del componente modificato.

## Progress Tracking

- 2026-07-17: story creata e baseline documentale generata da Codex (GPT-5).
- 2026-07-17: report corretti con file, rilievi e modifiche effettive per componente dopo feedback utente.

---

Story creata con BMAD Method v6 — Phase 4, Implementation Planning.
