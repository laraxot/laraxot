---
title: "Errori commessi nel porting gestionale_commesse → base_workorder_fila5 (2026-07-23)"
type: retrospective
tags: [errori, lessons-learned, gestionale-commesse, porting, multi-agent, retro]
created: 2026-07-23
updated: 2026-07-23
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./gestionale-commesse-comparison/EPIC.md"
  - "./gestionale-commesse-comparison/roadmap.md"
  - "../docs/wiki/concepts/porting-external-module-playbook.md"
---

# Errori commessi nel porting gestionale_commesse → base_workorder_fila5

> Retrospettiva onesta della sessione 2026-07-23 (porting di 23 moduli da `_bases/gestionale_commesse`
> in questo monorepo). Obiettivo: non rifare questi errori in sessioni future. Ogni voce ha causa,
> impatto, correzione applicata, e dove è tracciata la regola per il futuro.

## 1. Fasi di porting collassate invece di separate

**Errore**: durante l'import di massa iniziale, più agenti paralleli hanno eseguito in un unico
passaggio copia + ristrutturazione completa in forma XotBase canonica (app/Models con XotBaseModel,
migrations 1-per-modello, Filament XotBaseResource...) + rename plurale→singolare, invece di limitarsi
a "copia as-is + rename singolare" come unico step della fase di import di massa.

**Impatto**: churn di codice non richiesto in quella fase, lavoro difficile da distinguere da un vero
adattamento mirato, difficoltà a capire cosa fosse "solo copiato" vs "già deciso architetturalmente".

**Correzione**: l'utente ha corretto esplicitamente; si è deciso di **mantenere** il lavoro di reshape
già fatto (valido e verificato) ma di applicare la sequenza corretta per il futuro: fase 1 = copia as-is
+ rename singolare; fase 2 = reshape, separata, innescata solo con un motivo concreto (collisione da
risolvere, abilitazione, lavoro reale su feature).

**Tracciato in**: `docs/wiki/concepts/porting-external-module-playbook.md` (sezione "Lezione appresa
2026-07-23"), memoria persistente `feedback_porting_phases.md`.

## 2. Doppie copie modulo (plurale grezza + singolare canonica) non riconciliate subito

**Errore**: per WorkOrder/WorkOrders, Intervention/Interventions, Quotation/Quotations,
Signature/Signatures, Vehicle/Vehicles, sono rimaste **entrambe** le cartelle in `laravel/Modules/`
per un periodo, con la plurale lasciata `disabled` in `modules_statuses.json` invece di essere
rimossa o esplicitamente archiviata non appena la singolare era stabile.

**Impatto**: confusione su quale fosse il modulo "vero", raddoppio della superficie grep/PHPStan,
rischio concreto che un agente editasse per errore la copia sbagliata (è successo: l'agente Billing
ha referenziato `Modules\WorkOrders\` plurale invece di `Modules\WorkOrder\` singolare).

**Correzione**: passata di riconciliazione dedicata per rimuovere le copie plurali una volta verificato
che nessun riferimento esterno le puntasse più.

**Tracciato in**: `docs/wiki/concepts/porting-external-module-playbook.md` (sezione "Anti-pattern
osservato: doppia copia non riconciliata").

## 3. Blocker di bootstrap condiviso non anticipato (routes mancanti)

**Errore**: molti moduli nuovi sono stati creati senza `routes/api.php`/`routes/web.php` (anche solo
stub), pur essendo richiesti obbligatoriamente da `XotBaseServiceProvider::register()`. Questo non è
stato verificato subito dopo la creazione di ogni modulo.

**Impatto**: **l'intero bootstrap Laravel falliva**, bloccando PHPStan/Larastan per *qualsiasi* modulo
del monorepo (non solo quello mancante) — molti agenti paralleli hanno perso tempo a diagnosticare
"PHPStan non parte" credendo fosse un problema del proprio modulo, quando la causa era altrove.

**Correzione**: creati gli stub mancanti in 12 moduli; il bootstrap ora procede e PHPStan gira davvero
(non solo `php -l`).

**Tracciato in**: `docs/wiki/concepts/porting-external-module-playbook.md` (sezione "Pitfall: bootstrap
applicativo condiviso e PHPStan/Larastan") — regola: ogni modulo nuovo deve avere subito lo scaffold
minimo di routes/provider, **prima** di scrivere Model/Action/Filament.

## 4. Studio della struttura canonica fatto a lavoro già iniziato, non prima

**Errore**: la richiesta di "studiare la struttura dei nostri moduli se non la conosci" è arrivata
correttamente, ma nella prima ondata di porting il riferimento (`canonical-module-structure.md`) è
stato scritto e poi immediatamente usato dagli stessi agenti in parallelo senza un momento di verifica
che fosse davvero corretto/completo prima di lanciare 10 agenti di conversione.

**Impatto**: rischio (parzialmente concretizzato, es. punto 2 e 3) che scelte fatte in fretta nel primo
modulo (es. status come tabella lookup vs enum PHP) si propagassero in modo incoerente sugli altri.

**Correzione**: non fatta retroattivamente in questa sessione (il lavoro già fatto resta); da applicare
al prossimo batch di porting: un modulo "pilota" completo e verificato prima di lanciare il resto in
parallelo, non tutti insieme dal primo tentativo.

## 5. Reazione ad azioni concorrenti trattate come collisione pericolosa

**Errore**: in una sessione precedente/parallela, rename e cancellazioni di directory pianificate
(es. `Vehicles` scomparsa, `AiAssistant` scomparsa) sono state inizialmente trattate come possibili
collisioni multi-agente allarmanti, quando invece erano parte del piano concordato.

**Impatto**: tempo speso a rassicurare/verificare invece che a procedere, e rischio di dare
l'impressione all'utente che il lavoro fosse a rischio quando non lo era.

**Correzione**: verificato con `module-mapping.md` e i doc di gap-fill che i cambiamenti erano
pianificati; spiegato il perché con evidenza concreta (grep di riferimenti, contenuto della doc di
port) invece di solo rassicurare a parole.

**Tracciato in**: memoria persistente `feedback_gestionale_commesse_module_naming.md` (sessione
precedente).

## 6. Bug introdotti durante il reshape non intercettati subito da PHPStan

**Errore**: `Modules\AI\...\ViewAiActionProposal` con metodo astratto non implementato,
`Modules\Employee\...\ListAbsenceRequests::getTableFilters` con visibilità di metodo errata rispetto
al parent — entrambi introdotti durante il reshape e scoperti solo quando PHPStan ha potuto finalmente
bootstrappare (vedi punto 3), non prima.

**Impatto**: bug rimasti latenti per diverse ore di lavoro parallelo prima di essere notati.

**Correzione**: fix dedicati assegnati a un agente specifico dopo la scoperta.

**Perché non prevenibile prima**: dipendeva dal blocco di bootstrap (punto 3) — altra ragione per cui
il pitfall routes/provider va risolto **per primo**, subito dopo la creazione di ogni modulo, non alla
fine del batch.

## Regola sintetica per il futuro

1. Fase 1 di un porting di massa: **solo** copia as-is + rename singolare. Reshape è fase 2 separata,
   su richiesta esplicita o necessità concreta.
2. Ogni modulo nuovo: scaffold minimo (`RouteServiceProvider`, `EventServiceProvider`,
   `routes/{web,api}.php`) **subito**, prima di qualunque altro file.
3. Mai lasciare coesistere a lungo una copia grezza e una normalizzata dello stesso concetto: riconciliare
   e rimuovere la grezza appena la normalizzata è stabile.
4. Un modulo "pilota" verificato end-to-end prima di lanciare N agenti paralleli sullo stesso pattern.
5. Prima di allarmarsi per un rename/cancellazione in un lavoro multi-agente pianificato, verificare il
   piano documentato (`module-mapping.md`, story) prima di trattarlo come collisione.
