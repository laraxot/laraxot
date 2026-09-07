---
id: no-numbered-filenames-bridge-dedup
slug: no-numbered-filenames-bridge-dedup
scope:
  - module:Xot
  - project:base_workorder_fila5
status: ready-for-dev
priority: Low
created: 2026-09-07
epic: 2.7.agents-skills-rules-on-demand-compact
---

## Problema

Story `laravel/Modules/Xot/docs/stories/18.4.md-filename-convention-audit-no-dates-no-numbered-suffix.story.md`
lascia un AC non chiuso: "Bridge rotto `no-numbered-filenames.md`
(auto-referenziale, canonico introvabile) da riparare separatamente."

Verifica 2026-09-07 (questa story): la diagnosi e' imprecisa. Il bridge
`bashscripts/ai/wiki/rules/no-numbered-filenames.md` punta a
`../../../../docs/wiki/rules/no-numbered-filenames.md`, che **esiste**
(350 byte, presente dal 31/08) — il link risolve correttamente, non e'
rotto e non e' auto-referenziale.

Il problema reale e' un altro: esistono **5 file** con lo stesso nome
`no-numbered-filenames.md` in punti diversi dell'albero regole, con
contenuto diverso e nessuna gerarchia esplicita tra loro:

- `bashscripts/ai/wiki/rules/no-numbered-filenames.md` (bridge, corretto)
- `bashscripts/ai/wiki/rules/misc/no-numbered-filenames.md` (bridge duplicato)
- `bashscripts/ai/wiki/rules/migrated-from-agents/no-numbered-filenames.md`
  (contenuto proprio, frontmatter `type: rule`)
- `bashscripts/ai/wiki/rules/code/no-numbered-filenames.md` (contenuto proprio)
- `bashscripts/ai/wiki/rules/11-forms/no-numbered-filenames.md` (contenuto
  proprio, stesso incipit di `code/`)
- `docs/wiki/rules/no-numbered-filenames.md` (canonico dichiarato dal bridge)

Un agente che cerca la regola con `qmd search` o `rg` puo' trovare una delle
4 copie non canoniche e non il canonico, con rischio di applicare una
versione stale della regola. Ironico dato il contenuto della regola stessa
(niente suffissi duplicati), ma il problema qui e' duplicazione di file
interi con lo stesso basename, non un suffisso numerico.

## Acceptance criteria

- [ ] Confermare quale file e' il canonico attuale (probabilmente
      `docs/wiki/rules/no-numbered-filenames.md`, verificare contenuto vs
      le altre 4 copie prima di decidere).
- [ ] Le copie in `misc/`, `migrated-from-agents/`, `code/`, `11-forms/`
      diventano bridge on-demand verso il canonico (stesso pattern del
      bridge gia' corretto in `bashscripts/ai/wiki/rules/`), oppure vengono
      rimosse se il contenuto e' identico e ridondante — non riscrivere il
      canonico da zero, solo ricondurre le copie.
- [ ] Correggere l'AC in `18.4.md-filename-convention-audit-no-dates-no-numbered-suffix.story.md`:
      il bridge principale non era rotto, il problema era la duplicazione;
      linkare questa story invece di lasciare la nota generica.
- [ ] `git mv`/link check: nessun riferimento incrociato rotto dopo la
      normalizzazione (`rg -l "no-numbered-filenames.md"` prima e dopo).

## Note

Rischio bassissimo, solo file di documentazione infra, nessun codice PHP
toccato. Non richiede modulo/tema dedicato: le regole on-demand vivono gia'
in `bashscripts/ai/wiki/rules/` e `docs/wiki/rules/`, entrambi esistenti.
