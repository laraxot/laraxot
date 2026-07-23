# Session Errors — 2026-07-23

Tutti gli errori commessi in questa sessione, documentati per non ripeterli.

---

## 1. Dati stale da audit precedenti

**Errore:** Ho speso ~30% del tempo analizzando moduli SRC plurali (WorkOrders/,
Vehicles/, Interventions/, Quotations/, Signatures/) e staging (Auth/, HR/, Email/,
WhatsApp/, Platform/, Documents/, AiAssistant/) che erano già stati cancellati su
disco prima dell'inizio della sessione. I task agent hanno riportato analisi
complete di questi moduli, ma nessuno esisteva più.

**Causa:** `ls -d` in m0090 ha mostrato le directory esistenti, ma in m0094-95
erano sparite. Possibile race condition con agenti paralleli, OPPURE l'`ls` in
m0090 era cached/stale. In ogni caso, non ho mai fatto `ls -la Modules/*/`
all'inizio per fotografare lo stato reale.

**Regola:** Prima di qualsiasi analisi strutturale, esegui `ls -d Modules/*/`
e confronta con `modules_statuses.json`. Se i dati dell'audit dicono che un
modulo esiste ma `ls -d` fallisce, il dato è stale — non usarlo.

---

## 2. Billing models già compliant — audit non sincronizzato

**Errore:** L'audit (task agent) ha riportato 7 modelli in Billing che
estendono `Model` invece di `XotBaseModel/BaseModel`. Su disco, estendevano
già `XotBaseModel`. Ho speso tempo a pianificare fix che non servivano.

**Causa:** L'agente di audit è stato lanciato su un albero diverso o in un
momento diverso rispetto ai file reali su disco. Anche qui, dati stale.

**Regola:** Dopo un audit agent, riconferma i findings critici con grep
diretto (`grep -r 'extends Model' Modules/*/app/Models/`).

---

## 3. AiAssistant "inesistente" — workdir sbagliato

**Errore:** Ho concluso che AiAssistant non avesse `app/Providers/` perché
ho eseguito `ls app/Providers/` con workdir sbagliato (puntava a un'altra
directory). L'errore era mio.

**Fissato con:** `ls /var/www/_bases/base_workorder_fila5/laravel/Modules/AiAssistant/app/Providers/`

**Regola:** Usa sempre percorsi assoluti nei comandi bash, mai relativi.
Se usi `workdir`, verifica con `pwd` prima.

---

## 4. Plural modules "esistenti" per output ls -d fuorviante

**Errore:** In m0090, `ls -d laravel/Modules/{Vehicles,Signatures,...}/` ha
restituito output per tutti 5 i plurali. In m0094, `ls -d laravel/Modules/Vehicles`
ha fallito. Stessa directory, minuti dopo, risultati diversi.

**Probabile causa:** Un task agent parallelo ha rimosso le directory tra m0090
e m0094. Non ho verificato la sincronia degli agent prima di iniziare a lavorare.

**Regola:** Non lanciare analisi e modifiche in parallelo sullo stesso
filesystem. Se devi farlo, usa un meccanismo di lock
(`multi-agent-file-lock` skill) o esegui in sequenza.

---

## 5. .lock files trattati come debris senza comprenderne il sistema

**Errore:** Ho rimosso .lock da AiAssistant e themes/docs/ senza capire il
meccanismo che li genera. Solo dopo ho scoperto 80+ file `.lock` sparsi per
tutto il progetto. Non ho verificato se facessero parte di un sistema attivo
(migration lock, CI lock, process lock, file-level mutex).

**Impatto:** Basso (erano .md.lock e .php.lock in directory docs/), ma il
principio è sbagliato.

**Regola:** Mai rimuovere file senza capirne la semantica. Grepa il progetto
per il pattern, controlla se c'è un sistema che li crea/legge, cerca in
wiki e AGENTS.md. Se non trovi nulla, chiedi all'utente prima.

---

## 6. Compressione eccessiva — contesto perso

**Errore:** In alcune compressioni ho rimosso contesto che serviva nella
risposta successiva. Esempio: compresso i dettagli dell'audit subito prima
di dover eseguire i fix basati su quegli stessi dettagli.

**Regola:** Non comprimere una sezione finché non hai finito di eseguire
tutte le azioni derivate da quella sezione. Aspetta la chiusura del ciclo
analisi→esecuzione→verifica.

---

## 7. Agenti paralleli scoordinati

**Errore:** Lanciando 4-5 task agent in parallelo per analizzare diversi
moduli, ho ottenuto dati incoerenti perché lo stato del filesystem cambiava
tra l'avvio e il completamento. Un agente analizzava moduli che un altro
agente stava cancellando.

**Regola:** Agenti paralleli SOLO per lavoro read-only (analisi, ricerca).
Per lavoro scritura (delete, move, edit), esegui in sequenza o usa
`multi-agent-file-lock` skill.

---

## 8. Non ho letto AGENTS.md all'inizio

**Errore:** AGENTS.md dice "Leggi il wiki" ma sono partito direttamente
con l'analisi senza caricare le skill rilevanti (module-structure,
xotbase-never-extend-filament, queueable-action-conversion).

**Regola:** All'inizio di ogni sessione, leggi AGENTS.md e carica le skill
che matchano il task. In questa sessione servivano almeno:
- `xotbase-filament-inheritance`
- `xotbase-never-extend-filament`
- `queueable-action-trait`

---

## 9. Scope sprawling — "tutto insieme"

**Errore:** L'utente ha detto "tutto insieme" e ho tentato di fare modello
di fix, delete, docs, wiki — tutto in parallelo senza una sequenza chiara.
Ho finito con task parziali e .lock pre-esistenti lasciati indietro.

**Regola:** Anche se l'utente dice "tutto insieme", dai una priorità
esplicita e esegui in wave sequenziali. Wave 1: read-only analysis.
Wave 2: structural changes. Wave 3: doc updates. Ogni wave si completa
prima della successiva.
