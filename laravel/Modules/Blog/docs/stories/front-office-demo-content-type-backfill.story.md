---
title: "Front office /it/blog e /it/events vuoti: backfill di type"
type: story
status: done
module: Blog
created: 2026-10-26
updated: 2026-10-26
tags: [bmad, story, blog, events, tickets, parental, backfill, demo]
qmd: "blog events vuoti type NULL content_type Parental backfill Post Event count zero popola front office"
related:
  - ./front-office-demo-content-population.story.md
  - ./article-type-parental-sti-post-event.story.md
  - ../bmad/content-type-contract.md
issues: []
discussions: []
---

# /it/blog e /it/events vuoti: backfill di `type`

Stato: **done** (blog ed eventi). I ticket sono popolati da un'altra sessione, vedi "Aperto".

## Richiesta

L'utente (2026-10-26): "popola /it/blog, /it/events e /it/tickets", con BMAD, second brain e ponytail.

## Stato prima (misurato sul DB `fixcity_data`, server :8000)

- `/it/blog`: "0 notizie trovate". `/it/events`: "Non ci sono ancora eventi pubblicati".
- `articles`: 15 righe `published`, tutte con `type` NULL. `content_type` valorizzato: 10 `post`, 5 `event`.
- `Post::count()` e `Event::count()` valevano 0.
- Gli slug del seeder di oggi (`piano-asfaltature-autunno`, `festa-delle-associazioni`) non c'erano.
  La story collegata dice "done" con 14 articoli e 547 ticket: il DB non era piu' in quello stato
  (120 ticket all'inizio). Perche' sia tornato indietro non e' stato determinato.

## Causa

`Post` e `Event` sono figli Parental (`type` = post|event). Le 15 righe sono del 15-16 ottobre,
scritte quando la discriminante era `content_type`. Il codice del modulo e' passato a `type`
(modifiche non committate nel repo Blog) e le righe vecchie sono diventate invisibili.

## Fatto

1. Backup della tabella: `articles-before-type-backfill.sql` nello scratchpad della sessione (15 righe).
2. Una sola `UPDATE` idempotente: `type = content_type` dove `type` e' NULL e `content_type` in (post, event).
   15 righe aggiornate. `content_type` non toccato.
3. Nessun codice nuovo, nessun seeder lanciato.

## Non fatto, e perche'

- `DemoContentSeeder` non l'ho eseguito io: le 15 righe sono complete (immagine, 4 blocchi, 26 commenti,
  date degli eventi) e il catalogo del seeder ha quasi-doppioni (sport nel verde, torneo/ciclabili).
  Pochi minuti dopo la mia verifica un'altra sessione l'ha lanciato (idempotente per slug): `Post` 10 -> 18,
  `Event` 5 -> 11. I quasi-doppioni quindi ci sono; non li ho rimossi.
- Nessuna migrazione per il backfill: e' un riallineamento di dati di sviluppo. Se serve negli altri
  ambienti va scritta come migrazione `UPDATE articles SET type = content_type WHERE type IS NULL`.

## Verifica

- `Post::count()` = 10, `Event::count()` = 5.
- `/it/blog` 200, "10 notizie trovate", primo titolo "Una segnalazione utile inizia da tre informazioni".
- `/it/events` 200, 5 eventi, primo "Passeggiata Quartiere senza ostacoli".
- Dettaglio `/it/blog/una-segnalazione-utile-inizia-da-tre-informazioni`: 200, sezione Commenti con 3 commenti.
- `/it/tickets` 200, "Pagina 1 di 23", 20 card con titoli veri, 107 risolte.

## Aperto

- **Decisioni in conflitto, da risolvere dall'utente.** `bmad/content-type-contract.md` (accepted, 2026-10-15)
  dice: tipo editoriale in `content_type`, `type` solo discriminatore tecnico, "seed e query usano solo
  `content_type`". La story Parental (2026-10-26, indicazione dell'utente "e' il post che puo essere type
  article / type event") fa l'opposto e il codice la segue. Il backfill rispetta il codice; il contratto e'
  da aggiornare oppure la direzione da riconfermare. La regola `fixcity-inbound-editorial` dice anch'essa
  "mai Parental `type` come rubrica".
- La migrazione `2026_10_26_add_content_type_to_articles.php` (20:29) riaggiunge `content_type`, che la story
  Parental aveva rimosso e che il codice non legge piu'.
- Ticket: `FixcityDemoSeeder` lanciato da un'altra sessione alle 20:39 UTC (pid 866737), a 488 righe
  su ~547 quando ho smesso di misurare. Non l'ho rilanciato per non duplicare.
- Tre ticket con titolo segnaposto ("Titolo segnalazione 2/3"): vengono da `LoadDesignComuniElencoDemoCardsAction`,
  copia delle card dimostrative ufficiali Design Comuni (parity). Non toccati.
- Il repo del modulo Blog ha 44 file modificati non committati da altre sessioni: nessun commit fatto qui.
