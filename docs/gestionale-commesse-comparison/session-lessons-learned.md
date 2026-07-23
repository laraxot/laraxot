---
title: "Lezioni apprese — sessione migrazione gestionale_commesse (2026-07-23)"
type: concept
tags: [gestionale-commesse, lessons-learned, postmortem, agent-mistakes, second-brain]
created: 2026-07-23
updated: 2026-07-23
qmd: "lessons learned errors postmortem AiAssistant collision guardrail concurrent agents themes two config"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./replica-singular-phase.md"
  - "./agent-errors-lessons.md"
  - "./laraxot-compliance-audit.md"
  - "../wiki/rules/module-name-singular-mandatory.md"
  - "../wiki/rules/replica-singular-no-merge-delete.md"
  - "../wiki/skills/bmad-create-story/stories/1.6.copy-reshape-src-modules.story.md"
  - "../wiki/skills/bmad-create-story/stories/1.10.agent-errors-replica-singular-guardrails.story.md"
  - "../../laravel/Modules/AiAssistant/docs/import-status.md"
---

# Lezioni apprese — sessione migrazione gestionale_commesse (2026-07-23)

> Postmortem onesto degli errori. Catalogo sintetico E1–E7: [agent-errors-lessons.md](./agent-errors-lessons.md). Story: [1.10](../wiki/skills/bmad-create-story/stories/1.10.agent-errors-replica-singular-guardrails.story.md).

## 1. Ho cancellato `Modules/AiAssistant/` senza chiedere conferma

**Cosa ho fatto:** ho letto la guardrail scritta in `story 1.6` ("moduli collidenti non creati come
gemelli, solo merge") e l'ho applicata alla lettera, cancellando la cartella `AiAssistant` appena
copiata, basandomi su un documento che non rifletteva più la volontà reale dell'utente in quella
sessione.

**Perché era sbagliato:** una guardrail scritta in un documento è un **default**, non un ordine
assoluto superiore a quanto l'utente chiede esplicitamente nella conversazione corrente. Cancellare
una cartella (anche se non committata) senza conferma è un'azione distruttiva — va sempre confermata
prima, specie quando il documento che la giustifica potrebbe essere obsoleto.

**Come l'ho corretto:** ripristinato da `/var/www/_bases/gestionale_commesse/Modules/AiAssistant`
(fortunatamente la sorgente originale esisteva ancora intatta). Rifatto tutto il reshape perso.

**Regola:** *prima di ogni azione distruttiva (delete, rm -rf, git reset/clean) su qualcosa che non ho
creato io in questa sessione, chiedere conferma esplicita — anche se un documento di progetto sembra
autorizzarla.* Vedi `docs/wiki/rules/multi-agent-file-lock.md` e la sezione "Executing actions with
care" delle istruzioni di sistema.

## 2. Non ho verificato che la guardrail "niente gemelli" fosse ancora valida prima di riapplicarla

**Cosa ho fatto:** anche dopo aver ripristinato `AiAssistant` la prima volta, non ho aggiornato subito
il documento sorgente della guardrail (`1.6...story.md`) per marcarla come superata. Un secondo
agente/processo concorrente ha quindi riletto lo stesso documento non aggiornato e ha ri-cancellato
`AiAssistant` una seconda volta.

**Perché era sbagliato:** quando una regola di progetto viene esplicitamente contraddetta
dall'utente, il documento che la contiene va aggiornato **subito**, non solo tenuto a mente nella
conversazione. Altrimenti chiunque (agente, sessione futura, altro processo concorrente) rilegge la
versione vecchia e ripete l'errore.

**Come l'ho corretto:** aggiornato `1.6...story.md`, `module-name-singular-mandatory.md`,
`laraxot-compliance-audit.md` con la correzione esplicita e un avviso "non cancellare per questo
motivo" con riferimento all'incidente.

**Regola:** *ogni volta che l'utente corregge una regola scritta in un documento di progetto,
aggiornare quel documento nello stesso turno, non solo agire di conseguenza nella chat.*

## 3. Ho generalizzato "niente collisione, merge" come principio, quando l'utente intendeva "replica sempre, solo nome singolare"

**Cosa ho fatto:** per Auth/HR/Documents/Email/WhatsApp/Platform ho applicato (ereditandola da un
documento precedente) la strategia "merge nel modulo nativo esistente" (Auth→User, HR→Employee, ecc.),
senza mettere in discussione se fosse ancora la strategia voluta.

**Perché era sbagliato:** l'utente ha dovuto correggermi due volte sullo stesso punto
("SBAGLIATO... non c'era problema di collisioni... li replicavamo, unica differenza il nome in
singolare"). Un'istruzione ripetuta due volte è un segnale che avrei dovuto notare la prima volta e
verificare con una domanda, invece di procedere sull'assunzione implicita di un documento vecchio.

**Come l'ho corretto:** riscritto `EPIC.md`, `1.6...story.md`, `module-name-singular-mandatory.md`,
`laraxot-compliance-audit.md` per riflettere: ogni modulo SRC = cartella propria singolare, il
confronto con moduli nativi resta solo analisi/gap, mai merge fisico.

**Regola:** *quando eredito una strategia da un documento scritto in una fase precedente della stessa
migrazione, verificarla esplicitamente con l'utente prima di applicarla su larga scala, invece di
darla per assodata.*

## 4. Bug di escaping backslash nella generazione di `composer.json` via Python

**Cosa ho fatto:** ho generato il campo PSR-4 autoload con un f-string Python
`f"Modules\\\\{m}\\\\"`, producendo un namespace con **doppio backslash** (`Modules\\Billing\\`
invece di `Modules\Billing\`) in 12 file `composer.json`.

**Perché era sbagliato:** confusione tra escaping Python (`\\` = un backslash letterale) e output
JSON (che a sua volta richiede l'escape). Non ho verificato il contenuto decodificato subito dopo la
generazione.

**Come l'ho corretto:** rieseguito lo script con `"Modules\\" + m + "\\"` (namespace corretto) e
verificato con un roundtrip Python `json.load` che il valore decodificato avesse un solo backslash.

**Regola:** *dopo aver generato un file con un template/script che coinvolge escaping (JSON dentro
Python, o simili), rileggere sempre il contenuto **decodificato**, non solo il testo grezzo del file,
prima di considerarlo corretto.*

## 5. Non ho controllato lo stato reale del filesystem prima di scrivere `composer.json` con autoload `app/`

**Cosa ho fatto:** per i primi 12 moduli "nuovi" ho scritto `composer.json` con autoload PSR-4
puntato direttamente a `app/`, assumendo che la struttura fosse già quella canonica.

**Perché era sbagliato:** i moduli erano ancora nello stato "copy as-is" con namespace alla radice
(`Domain/`, `Application/`, `Infrastructure/`), non sotto `app/`. Un autoload a `app/` avrebbe rotto
il caricamento delle classi reali.

**Come l'ho corretto:** verificato con `find`/`grep` il namespace reale nei file PHP prima di
scrivere l'autoload, correggendolo a `""` (root modulo) per lo stato transitorio pre-reshape.

**Regola:** *non scrivere mai un file di configurazione (autoload, provider, migrazioni) basandosi
sulla struttura "target" desiderata — verificare sempre lo stato **attuale** del filesystem con un
comando prima.*

## 6. Non ho notato subito i processi/agenti concorrenti sullo stesso albero

**Cosa ho fatto:** per gran parte della sessione ho lavorato assumendo di essere l'unico agente ad
operare sull'albero `laravel/Modules/`, mentre in realtà altri processi/agenti stavano modificando
gli stessi file in parallelo (visto poi da modifiche non mie a `WorkOrder/app/Models/WorkOrder.php`,
`modules_statuses.json`, e ai file `docs` di `AiAssistant`).

**Perché era sbagliato:** questo ha causato duplicazione di lavoro (composer.json già esistenti
ricreati), incoerenze (guardrail vecchie riapplicate da altri processi), e la cancellazione ripetuta
di `AiAssistant`. Non ho segnalato subito questa possibilità all'utente, nonostante il repo abbia una
skill dedicata (`multi-agent-file-lock`, `multi-agent-lock-coordination`) proprio per questo scenario.

**Come dovrei correggere in futuro:** appena rilevo modifiche a file che non ho toccato io (via
system reminder o diff inatteso), verificare subito con `git status`/`git diff` e segnalarlo
esplicitamente all'utente, invece di limitarmi a "prendere atto" e continuare.

**Regola:** *in un repo con più agenti AI configurati (vedi CLAUDE.md root: Claude Code / Codex CLI /
ZCode), assumere per default che possano esserci processi concorrenti; controllare `git status` con
più frequenza del solito e segnalare tempestivamente eventuali modifiche esterne inattese.*

## 7. Ho corretto `pub_theme => 'Two'` in modo incompleto la prima volta

**Cosa ho fatto:** ho corretto `config/xot.php` e altri 5 file con un `sed` mirato sulla stringa
esatta `'pub_theme' => 'Two',`, senza cercare **tutte** le varianti/directory di config
(`config/com/sottana*`, `config/net/sottana*`) né considerare che più chiavi duplicate nello stesso
array PHP fanno vincere l'ultima.

**Perché era sbagliato:** ho dichiarato il problema risolto dopo il primo giro, ma PHPStan ha
ricominciato a fallire per lo stesso motivo (`Themes/Two` non trovato) quando ho ritestato più tardi,
perché altri file config con lo stesso problema non erano stati coperti dal primo giro mirato.

**Come l'ho corretto:** rieseguito un `grep -rl "'pub_theme' => 'Two',"` su tutta la cartella
`config/` per trovare *tutte* le occorrenze, non solo quelle già note, e le ho sostituite tutte.

**Regola:** *quando correggo un valore di configurazione che sospetto duplicato/sparso, cercare con
un grep ricorsivo su tutta la cartella pertinente PRIMA di dichiarare il fix completo, non fermarmi
alla prima lista di file trovata.*

## Sintesi — checklist da applicare sempre d'ora in poi

1. Azioni distruttive (delete/rm/reset) su file non creati in questa sessione → chiedere conferma,
   anche se un documento sembra autorizzarle.
2. Regola di progetto corretta dall'utente → aggiornare il documento sorgente nello stesso turno.
3. Istruzione ripetuta due volte dall'utente → segnale di malinteso mio, verificarla esplicitamente
   invece di procedere sull'assunzione precedente.
4. File generati via script con escaping (JSON/Python, YAML/heredoc) → rileggere il contenuto
   decodificato prima di considerarlo corretto.
5. Config/autoload/provider → verificare lo stato attuale del filesystem, mai assumere la struttura
   "target".
6. Modifiche a file non toccati da me → verificare `git status`/diff e segnalare, ipotizzando processi
   concorrenti.
7. Fix di valori di config sospetti duplicati/sparsi → grep ricorsivo esaustivo prima di dichiarare
   il fix completo.
