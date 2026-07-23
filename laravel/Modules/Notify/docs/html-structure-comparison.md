---
title: "HTML Structure Comparison Tools"
type: concept
tags: [html, structure, comparison]
created: 2026-07-14
updated: 2026-07-14
qmd: "html-structure-comparison html structure comparison tools"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index-2.md"
  - "./00-index.md"
  - "./absolute-completion-100.md"
  - "./acronym-naming-conventions-1.md"
  - "./acronym-naming-conventions-2.md"
  - "./acronym-naming-conventions.md"
  - "./action-plan-immediate.md"
---

# HTML Structure Comparison Tools

Bridge document tra tooling agnostico in `bashscripts` e output di progetto nel tema Sixteen.

## Canonical Tooling

- Wrapper: `bashscripts/html/html-structure-compare.sh`
- Engine: `bashscripts/html/compare-html-body.py`
- Bashscripts doc: `bashscripts/docs/HTML-COMPARISON.md`

## Canonical Sixteen Output

Per le pagine di test, gli snapshot e i report devono stare qui:
- `laravel/Themes/Sixteen/docs/prompts/<pagina>/`
- `laravel/Themes/Sixteen/docs/prompts/<pagina>/body-structure-comparison/`

I vecchi output in `laravel/Themes/Sixteen/docs/body-structure-comparison/` restano artefatti legacy e non sono piu il target canonico.

## Example

```bash
bashscripts/html/html-structure-compare.sh \
  "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-03-riepilogo.html" \
  "http://127.0.0.1:8000/it/tests/segnalazione-03-riepilogo" \
  "segnalazione-03-riepilogo" \
  "laravel/Themes/Sixteen/docs/prompts/segnalazione-03-riepilogo/body-structure-comparison" \
  "90" \
  "body"
```

## Governance

- `bashscripts` non deve conoscere percorsi del tema.
- La fase canonical di parity confronta il root `body`, salvo audit espliciti sul root `html`.
- Le blade di test usano `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`.
- La layout corretta e `<x-layouts.app>`.
- Le stringhe nelle blade devono passare da traduzioni a 5 livelli: `fixcity::contesto.collezione.chiave.tipo`.
- Nel markup possiamo mantenere le classi Bootstrap Italia per parity HTML, ma senza caricare Bootstrap CSS/JS.
- Comportamenti interattivi: TailwindCSS + Alpine.js.
- Il report canonico deve distinguere `identical`, `different`, `missing`, `extra` e produrre un parity score realistico.

## Related Docs

- `bashscripts/docs/HTML-COMPARISON.md`
- `laravel/Themes/Sixteen/docs/prompts/segnalazione-03-riepilogo/`