---
title: "DCP-430: lista-categorie con le classi Design Comuni al posto di Tailwind"
type: story
status: ready-for-dev
theme: Sixteen
created: 2026-10-27
updated: 2026-10-27
tags: [bmad, story, sixteen, lista-categorie, design-comuni, data-dc, tailwind, css]
qmd: "lista-categorie LC-02 LC-03 LC-04 tailwind spento data-dc CTA invisibile griglia classi design comuni screenshot"
related:
  - ../../../../Modules/Fixcity/docs/stories/dcp-408-lista-categorie-senso-origine-e-difetti-misurati.story.md
  - ./06-categories-list-design-comuni-redesign.story.md
issues: []
discussions: []
---

# DCP-430: lista-categorie con classi DC

## Origine

DCP-408, difetti LC-02, LC-03, LC-04 (proprietario: Sixteen). Nel CSS compilato del tema ogni utility Tailwind e' avvolta in `:not(:where(body:has(.page-content[data-dc]), ... *))`, cioe' spenta nelle pagine `data-dc`: e' voluto, isola Design Comuni. Le view della pagina usano pero' Tailwind: la CTA primaria «Segnala un problema» e' bianco su bianco (`text-white`, `bg-primary-700` spenti); la griglia non esiste (`sm:grid-cols-2`, `lg:grid-cols-3` assenti, 0 blocchi `@media` 40/48/640rem); le card non hanno padding e `h2/h3` sono enormi.

## Acceptance criteria

1. Le view della pagina (corpo dalla Folio page `Modules/Fixcity/resources/views/pages/lista-categorie.blade.php`, componente `Themes/Sixteen/resources/views/components/pages/categories.blade.php`) usano classi Design Comuni e non utility Tailwind; l'isolamento `data-dc` non si tocca.
2. La CTA primaria «Segnala un problema» e' visibile e leggibile (contrasto AA), con la seconda CTA «Consulta le segnalazioni» accanto.
3. Le card stanno in griglia: una colonna a 375 px, due a 768 px, tre a 1440 px; nessun overflow orizzontale; titoli di dimensione coerente con le altre pagine DC.
4. Prima e dopo: screenshot a 375, 768 e 1440 px nelle quattro lingue, misurati (box delle card, contrasto della CTA), non solo guardati.
5. Prima di usare una classe si controlla che esista nel CSS compilato (`grep` nel build): il tema e' un repo git separato. Dopo `npm run build` si controlla che in `public/design-comuni/assets/images` non ci siano file a 0 byte: il build copia le immagini da `docs/Main_files` e ne ha gia' azzerate in passato.
6. Nessun file nuovo in `Themes/Sixteen/resources/views/pages` e nessun alias: la regola del tema resta valida.
7. Il gate di non regressione esistente per la parity continua a passare.

## Tasks

- [ ] Leggere [06-categories-list-design-comuni-redesign](./06-categories-list-design-comuni-redesign.story.md) e annotare cosa si sovrappone; collegarla o marcarla superseded, senza cancellarla.
- [ ] Elencare le classi DC equivalenti a quelle usate (griglia, card, pulsante primario) guardando una pagina DC gia' verificata.
- [ ] Sostituire le classi nelle due view.
- [ ] Build del tema e controllo che non ci siano file a 0 byte nelle immagini copiate.
- [ ] Screenshot e misure prima/dopo.
- [ ] Commit nel repo del tema e nel repo del modulo Fixcity (due repo diversi).

## Verifica richiesta

Screenshot con le misure dichiarate; output del `grep` che prova l'esistenza delle classi; esito del gate di parity.

## Owned file scope

`laravel/Themes/Sixteen/resources/views/components/pages/categories.blade.php`, CSS del tema solo se manca una classe DC; `laravel/Modules/Fixcity/resources/views/pages/lista-categorie.blade.php` per le classi della Folio page (coordinare con DCP-408/DCP-421/DCP-423 che toccano gli stessi file).

## Coordinamento

| Data | Agente | Scope | Nota |
|------|--------|-------|------|
| 2026-10-27 | Claude | stesura | nessun lavoro avviato; DCP-408 e' in corso di un'altra sessione |
