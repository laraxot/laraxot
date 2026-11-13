---
id: events-public-index
status: done
title: Indice pubblico eventi
---

## Story

Come cittadino voglio trovare gli appuntamenti futuri della comunità in una lista
chiara e localizzata, così posso scegliere un evento e aprirne il dettaglio.

## Acceptance criteria

1. `/it/events` risponde 200 e rispetta il contratto JSON della pagina CMS.
2. Sono mostrati solo `Article` pubblicati con `content_type=event` e
   `event_start_date > now()`; gli eventi conclusi non sono pubblici nell'indice.
3. Ogni card ha titolo, immagine con testo alternativo, data/ora e link al dettaglio.
4. Il dettaglio `/it/events/{slug}` risponde 200 e mantiene breadcrumb e ritorno
   alla lista eventi.
5. Il rendering non assume che le date Eloquent siano già Carbon: normalizza il
   valore al bordo della view.
6. La pagina vuota e la paginazione sono accessibili e localizzate.

## Technical notes

- L'indice pubblico non mostra i luoghi di esempio del riferimento statico (castello, Gipsoteca, Colosseo). L'immagine in testa, se manca nel JSON, è la foto del primo evento reale.
- Nessun modulo Event aggiuntivo: il contratto riusa `Blog\Models\Article`.
- `content_type` è il discriminante editoriale; `type` resta riservato a Parental.
- `ThemeComposer::paginatedPublicEvents()` riusa `ThemeArticleQueries::paginatedComingSoon()`.
- `ResolvePageAction` conserva il fallback al modello Meetup prima dell'Article.

## Testing strategy

- Feature test per query, esclusione degli eventi passati e risposta HTTP dell'indice.
- Smoke test HTTP per dettaglio e verifica assenza di eccezioni nella risposta.
- PHPStan sul perimetro `Modules`.

## Owned scope

- `Modules/Blog/app/View/Composers/`
- `Modules/Blog/app/Models/Article.php`
- `Modules/Blog/tests/Feature/`
- `Modules/Cms/app/Actions/ResolvePageAction.php`
- `Themes/Sixteen/resources/views/components/blocks/events/`
- `config/local/fixcity/database/content/pages/events.index.json`

## Filament Blueprint

Il resource editoriale deve esporre `content_type=event`, titolo, slug, stato,
immagine, excerpt, contenuto, categoria, `event_start_date` obbligatoria e
`event_end_date` opzionale. La tabella deve filtrare gli eventi senza introdurre
un resource duplicato: stesso Article Resource, vista filtrata per content type.
