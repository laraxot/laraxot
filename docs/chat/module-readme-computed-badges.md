---
title: "Module README — marketing + badge calcolati (claim)"
type: chat-handoff
tags: [readme, badges, modules, agnostic, bmad]
created: 2026-09-03
updated: 2026-09-03
agent: claude-fable-5.1
story: laravel/Modules/Xot/docs/stories/18.1.module-readme-agnostic-computed-badges.story.md
status: in-progress
---

# Claim: rigenerazione README.md di tutti i moduli

Agente: claude-fable-5.1 (sessione 2026-09-03). Lock attivi su `laravel/Modules/*/README.md` (task `readme-computed-badges`).

Regole decise con il maintainer:

- Moduli e temi sono **agnostici**: nessun riferimento a FixCity o ad altri progetti host. FixCity e' un progetto diverso.
- Badge **calcolati** da script (`bashscripts/tools/readme/module-readme-badges.sh`), non copiati a mano.
- Badge GitHub dinamici solo per repo pubbliche (verificate via API); repo private ricevono solo badge locali.
- Nessun badge verso servizi inesistenti o id inventati (herokuapp lines-of-code, codeclimate, `commit-hourglass`).

Un processo concorrente (`opencode`) ha riscritto ~40 README negli ultimi 20 minuti con contenuto FixCity e badge finti: verra' sovrascritto dal generatore. Non toccare i README finche' il lock e' attivo.
