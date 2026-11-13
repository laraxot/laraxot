---
title: "Inventario della documentazione - Tema Zero"
type: note
module: Zero
status: active
updated: "2026-10-08"
id: zero-docs-inventory
slug: inventory
description: "Questa nota descrive la struttura reale di laravel/Themes/Zero/docs/ al"
document_type: note
category: documentation
tags: []
created: "2026-10-08"
qmd: "inventario della documentazione - tema zero"
issues: []
discussions: []
---

# Inventario della documentazione - Tema Zero

Questa nota descrive la struttura reale di `laravel/Themes/Zero/docs/` al
2026-10-08. È un riferimento operativo: non sposta, rinomina o sostituisce i
documenti storici.

## Punti di ingresso

- [README](./README.md): entrypoint mantenuto per la navigazione umana.
- [Wiki index](./wiki/index.md): catalogo della conoscenza curata e caricata
  on-demand.
- [BMAD index](./bmad/00-index.md): guide, decisioni e artefatti di
  pianificazione del tema.
- [Changelog](./changelog.md): cronologia documentata.
- [Stories](./stories/): attività e audit incrementali.

## Struttura reale

| Percorso | Ruolo | Documenti Markdown |
|---|---|---:|
| `bmad/` | pianificazione, architettura, guide e report | 125 |
| `wiki/` | conoscenza curata, regole, how-to e fonti | 55 |
| `stories/` | storie operative e audit | 5 |
| `concepts/` | concetti specifici del tema | 1 |
| `skills/` | note sulle skill | 1 |
| `_archive/` | materiale storico non canonico | 19 |
| root (`docs/`) | entrypoint e cronologia | 4 |

La directory `wiki/` contiene inoltre le aree `commands`, `comparisons`,
`concepts`, `decisions`, `entities`, `glossary`, `how-to`, `lint`, `memories`,
`overviews`, `queries`, `raw`, `reference`, `rules`, `screenshots`, `skills`,
`sources` e `summaries`. Alcune sono predisposte con `.gitkeep` e non hanno
ancora pagine.

## Stato dei controlli

Misurati a fine sessione del 2026-10-08 con le classi di `bashscripts/quality-gates/audit-module-docs.sh` applicate alla sola cartella Zero.

- Totale: **210** file Markdown.
- Front matter completo (`id`, `slug`, `title`, `description`, `document_type`, `category`, `status`): **210/210**. All'inizio della sessione 0/209 (25 senza front matter, 184 incompleti).
- Marker di conflitto Git: **0 file**. All'inizio 95 file / 501 righe: 65 file risolti per intero da questa sessione senza perdita (un lato vuoto o lati identici), piu' i blocchi banali di altri 7; i 30 file rimasti, con lati divergenti o annidati, sono stati risolti scegliendo un lato da un altro attore.
- Link relativi: **1.215** controllati, **509** non risolti. All'inizio 1.406 e 968. Cause: percorsi di altri moduli, nomi ambigui, file mai esistiti.
- File `superseded`: 6. File in `_archive/` con `canonical:`: 19. Id duplicati: 0.
- Gli entrypoint operativi `README.md`, `bmad/README.md`, `inventory.md` e `wiki/index.md` non hanno link locali non risolti.

## Regola per i prossimi incrementi

Usare `README.md`, `wiki/index.md` o `bmad/00-index.md` in base al tipo di
contenuto. Aggiungere una pagina nella directory più specifica già esistente,
aggiornare il relativo indice e lasciare `_archive/` immutato salvo una
richiesta esplicita di riconciliazione storica.
