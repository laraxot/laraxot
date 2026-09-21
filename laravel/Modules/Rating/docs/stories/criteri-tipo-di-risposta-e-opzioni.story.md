---
name: criteri-tipo-di-risposta-e-opzioni
description: "Due analisi accoppiate: dove vivono le opzioni di un criterio a scelta, e come si dichiara il tipo di risposta (numerico, scelta, testo) con la regola condizionale «Altro → testo obbligatorio». Solo brainstorming: nessun codice, decisioni in capo all'utente."
metadata:
  type: story
  status: awaiting-decision
  agent: claude opus 5 — sessione base-ptvx-fila5-92 [6a95f1]
  module: Rating
  related: 18.46.analisi-criteri-scelta-e-tipo-campo
---

# Story — tipo di risposta di un criterio e opzioni del select

> **Nessun codice.** L'utente ha chiesto esplicitamente due brainstorming, non
> un'implementazione. Questa story esiste per tracciare le decisioni aperte e per
> impedire che qualcuno implementi una delle due analisi senza l'altra: **sono
> accoppiate**, e sceglierle separatamente produce due modelli che non si parlano.

> **Story parallela**: `27de9782` ha aperto
> [18.46](18.46.analisi-criteri-scelta-e-tipo-campo.story.md) sugli stessi due temi,
> con altri tre documenti. Nessuna delle due annulla l'altra: il consolidamento e'
> tracciato in [18.54](18.54.cinque-documenti-due-temi-consolidamento.story.md).

## Documenti

| documento | domanda |
|---|---|
| [`../select-nelle-valutazioni-analisi.md`](../select-nelle-valutazioni-analisi.md) | dove vivono le **opzioni** di un criterio a scelta: JSON `data`, padre/figlio, `criteri_options`, altre 4 strade |
| [`../tipo-di-risposta-del-criterio-analisi.md`](../tipo-di-risposta-del-criterio-analisi.md) | come si dichiara il **tipo di risposta** e la regola «Altro → testo obbligatorio» |

## Perché sono una cosa sola

La regola condizionale chiude il cerchio: «se scegli *Altro* il testo è obbligatorio»
richiede un flag **sull'opzione**. Quindi la seconda analisi ha bisogno di sapere dove
sono finite le opzioni della prima. Decidere «opzioni in JSON» e poi «flag su colonna»
non è componibile.

## Fatti misurati che vincolano ogni scelta

| | |
|---|---|
| `rating_morph.value` | `decimal(10,3)` — una risposta testuale non ci sta |
| `rating_morph.note` | `text` — l'unico posto per il testo, ma significa già «annotazione» |
| `rating_morph` righe | 2.298 in IR, 0 nelle altre due installazioni |
| `ratings.rule` (`RuleEnum`) | contiene **stringhe di validazione** (`numeric\|min:0\|max:5`), non tipi di widget |
| `ratings.extra_attributes` | schemaless, presente, già usata |
| tabella `options` | morph (`option_type`/`option_id`), con `name`, `value`, `pos`, `year`, `parent_id`, `txt`, `txt1` — **sulla connessione del modulo**, stesso database dei rating |
| `options.option_id` vs `ratings.id` | `int(10) unsigned` contro `bigint(20) unsigned`: chiave esterna più stretta della chiave |
| `criteri_options` / `criteri_esclusione` | **altro dominio**: chi partecipa, non come si valuta. Vedi la correzione sotto |

## Decisioni in capo all'utente

- [ ] dove vivono le opzioni (la prima analisi raccomanda `options` come morph del criterio)
- [ ] nome del campo del tipo — `type` è libero, ma non dice quale asse descrive
- [ ] `options.option_id`: si allarga a `bigint` o si accetta il restringimento
- [ ] intero e decimale: un tipo con precisione, o due tipi
- [ ] select e radio: un tipo con resa, o due tipi
- [ ] dove va una risposta testuale: `note` riusata o colonna nuova
- [ ] come si evita che `input_type` e `rule` si contraddicano: derivare, verificare, o niente
- [ ] «Altro»: flag sull'opzione o riconoscimento dal nome

## Conseguenza già nota, da non dimenticare

Il filtro e la colonna consegnati oggi definiscono «valutata» come `value` non nullo e
diverso da zero (vedi [`ratings-column-section-filter.story.md`](ratings-column-section-filter.story.md)).
Se una risposta valida può essere **solo testo**, quella definizione smette di valere e
la lista dirà «non valutata» a schede compilate. Va rivista **nello stesso rilascio**,
non dopo.

## Correzione registrata

La prima stesura di entrambi i documenti proponeva `criteri_options` come casa delle
opzioni di valutazione, e citava `criteri_options.type` / `criteri_esclusione.type`
come vocabolario in conflitto. **Sbagliato**: quelle tabelle appartengono al dominio
dell'esclusione — *chi partecipa* — con chiave `anno` e proprietario Ptv, e contengono
parametri di campagna (`min_gg_asz_tip_cod_escluso_subito = 730`,
`lista_posiz_tempo_determinato = "2,21"`). I rating rispondono a un'altra domanda,
*come si valuta chi partecipa*.

Corretto dall'utente, verificato sui dati, entrambi i documenti riscritti nelle parti
coinvolte. La raccomandazione è cambiata di conseguenza: da `criteri_options` a
`options` come morph del criterio.

Causa: **il nome condiviso**. Cercando «criteri» si trovano le tabelle dell'altro
dominio, e sembrano pertinenti perché si chiamano come la cosa cercata. La somiglianza
di forma (`nome => valore`, per anno) ha poi confermato l'abbaglio. Il segnale
contrario c'era ed è stato scritto senza essere letto: **0 righe in IR**. Una tabella
«che esiste già per questo» che nessuno ha mai riempito in quel modulo, non è la
tabella per quello.

## Claim

Nessuno. Non c'è lavoro da svolgere finché le decisioni sopra non sono prese.
