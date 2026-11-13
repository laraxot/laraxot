---
title: "DCP-439: il piano docs di Sixteen dice il falso su chi serve lista-categorie"
type: story
status: ready-for-dev
module: Sixteen
created: 2026-10-27
updated: 2026-10-27
tags: [bmad, story, sixteen, docs, lista-categorie, folio]
qmd: "docs sixteen lista-categorie servita folio services.categories container0 docs-cleanup-story D-DOC-02"
related:
  - ../plans/docs-cleanup-story.md
  - ../../../../Modules/Fixcity/docs/stories/dcp-408-lista-categorie-senso-origine-e-difetti-misurati.story.md
  - ./dcp-430-lista-categorie-classi-design-comuni-al-posto-di-tailwind.story.md
---

# DCP-439: il piano docs di Sixteen dice il falso su chi serve lista-categorie

## Richiesta (verbatim)

«crea tutte le bmad stories». Origine: l'utente ha chiesto di studiare e correggere i docs di moduli e temi su `/it/lista-categorie`.

## Evidenza (2026-10-27)

`Themes/Sixteen/docs/plans/docs-cleanup-story.md` dice, alla decisione D-DOC-02 (riga 13) e al paragrafo 1 (righe 24-26), che `lista-categorie` e' una pagina CMS servita da `[container0]/index.blade.php` con il JSON `lista-categorie.index`.

`php artisan folio:list` risponde invece, per `/de/lista-categorie` e `/en/lista-categorie`:

```
GET /de/lista-categorie  services.categories  Modules/Fixcity/resources/views/pages/lista-categorie.blade.php
GET /de/{container0}     container0.index     Themes/Sixteen/resources/vi...
```

La pagina del modulo Fixcity e' piu' specifica e vince; il suo corpo e' `@include('pub_theme::components.pages.categories')`, cioe' il componente del tema. Il JSON `lista-categorie.index.json` esiste (in `Modules/Fixcity/config/database/content/pages/` e in `config/local/fixcity/database/content/pages/`), ma non e' dimostrato che renda il corpo.

**Non verificato**: la riga per `/it/lista-categorie` non e' comparsa nell'estratto che ho letto (limitato a otto righe). Va rilanciato `folio:list` senza limite prima di correggere il piano.

## Relazione con DCP-423

Il criterio 2 di [DCP-423](../../../../Modules/Fixcity/docs/stories/dcp-423-lista-categorie-titolo-link-in-ingresso-e-tassonomia.story.md) (LC-10) chiede la stessa correzione senza dire cosa e' falso. Questa story ne e' il dettaglio con la prova e il file proprietario (il tema). Non e' un doppione da cancellare: chi chiude una story spunta l'altra.

## Acceptance criteria

1. Le righe 13 e 24-26 del piano dicono quale file serve la pagina, con il percorso letto dal codice il giorno della correzione.
2. Il piano rimanda a [DCP-408](../../../../Modules/Fixcity/docs/stories/dcp-408-lista-categorie-senso-origine-e-difetti-misurati.story.md) e a [DCP-430](./dcp-430-lista-categorie-classi-design-comuni-al-posto-di-tailwind.story.md).
3. Nessun file nuovo e nessuna pagina nel tema (regola sulle pagine del tema).
4. Il nome di ogni `.md` toccato passa `python3 -I bashscripts/tools/docs/md-filenames.py name <percorso>`.

## Tasks

- [ ] `php artisan folio:list` completo, per it, en, de, es.
- [ ] Correggere le righe del piano; se il JSON non serve il corpo, dirlo.
- [ ] Rieseguire `folio:list` e confrontare con il testo scritto.

## Owned file scope

`laravel/Themes/Sixteen/docs/plans/docs-cleanup-story.md`. Solo documentazione, nessun test applicativo.

## Coordinamento

| Data | Agente | Scope | Nota |
|------|--------|-------|------|
| 2026-10-27 | Claude | stesura | nessun lavoro avviato |
