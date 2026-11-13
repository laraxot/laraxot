---
type: story
tags: [designers-italia, linee-guida-design, bmad]
created: 2026-10-14
updated: 2026-10-14
qmd: "linee guida design designers italia AGID CAD"
issues: []
discussions: []
id: "2026-10-14-dcp-159"
epic: 159
title: "Linee guida di design (Designers Italia) — riferimento e audit aderenza"
slug: linee-guida-design-audit
status: ready-for-dev
scope: theme:Sixteen
github:
  repository: https://github.com/laraxot/module_gdpr_fila5
  issues: https://github.com/laraxot/module_gdpr_fila5/issues/59
  discussions: https://github.com/laraxot/module_gdpr_fila5/discussions/60
  issue_status: "aperta"
related:
  - ../docs/stories/dcp-156-pagine-legali-complete-a-norma.story.md
  - ../../Xot/docs/stories/phpstan-cleanup-xot-app.story.md
---

# 2026-10-14 DCP-159 Linee guida di design (Designers Italia) — riferimento e audit aderenza

## Fonte studiata

`https://docs.italia.it/italia/designers-italia/design-linee-guida-docs/it/stabile/` (versione 2020.1, **superata**).

I riferimenti attuali sono:
- [Linee guida di design per i siti e i servizi digitali della PA](https://docs.italia.it/italia/design/lg-design-servizi-web) (norma CAD ex art. 71).
- [Manuale operativo di design](https://docs.italia.it/italia/designers-italia/manuale-operativo-design-docs/).

## Struttura delle linee guida (6 aree)

1. **Service design** — principi, gestione progetti, accessibilità, normativa.
2. **Prototyping** — user stories, prototipi bassa/media definizione.
3. **Content design** — architettura dell'informazione, SEO, linguaggio.
4. **User research** — usabilità, ricerche qualitative/quantitative, web analytics (incluso cookie).
5. **User interface** — principi, UI Kit, Web Kit (Bootstrap Italia), contribuire ai Kit.
6. **Kit di sviluppo e design** — kit per comuni, scuole, ecc.

## Obiettivo

- Documentare nel wiki la mappa delle linee guida e l'audit di aderenza del tema Sixteen.
- Verificare che il tema rispetti i principi: cittadini al centro, accessibilità, linguaggio semplice, UI Kit Bootstrap Italia, prototipazione.

## Accettazione

1. Pagina wiki con la mappa delle 6 aree e i link ufficiali.
2. Audit: elenco punti di forza e gap (es. UI Kit Bootstrap Italia vs Tailwind+Alpine+Lit).
3. Nessun file creativo in `app/Http/Controllers`.