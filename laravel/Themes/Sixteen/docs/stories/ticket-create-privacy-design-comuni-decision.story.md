---
id: dcp-456-sixteen
title: "Sixteen: passo privacy tickets/create con informativa a due livelli"
type: story
status: review
module: Sixteen
owner: theme-sixteen
priority: P1
created: 2026-11-12
updated: 2026-11-12
tags: [bmad, ecc, design-comuni, wizard, privacy, progressive-disclosure, wcag]
qmd: "Sixteen tickets create privacy Design Comuni avviso breve link informativa a due livelli"
discussion: "https://github.com/laraxot/theme_sixteen_fila5/discussions/96"
reference: "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html"
related:
  - "../../../../Modules/Fixcity/docs/stories/dcp-456-passo-1-del-wizard-con-informativa-a-due-livelli.story.md"
  - "../../../../Modules/Fixcity/docs/stories/ticket-create-privacy-avviso-breve.story.md"
---

# DCP-456 — decisione Sixteen sul testo del passo privacy

## Problema e senso

Il primo passo deve spiegare in pochi secondi che cosa sta accettando il cittadino
e dove leggere i dettagli. L'informativa completa è un documento legale; inserirla
nel wizard duplica la fonte e nasconde il consenso in una pagina sproporzionata.
Il riferimento Design Comuni adotta quindi una informativa a due livelli:
avviso essenziale nel flusso e approfondimento raggiungibile dal link.

## Decisione e differenze

La decisione, le alternative scartate e il razionale sono registrati nella
[GitHub discussion #96](https://github.com/laraxot/theme_sixteen_fila5/discussions/96).

| Prima | Decisione | Perché |
|---|---|---|
| `TicketForm::getGdprHtml()` passava tutto il markdown della policy | Renderizzare solo `gdpr_notice.text` localizzato | Riduce il carico cognitivo senza togliere l'informazione essenziale |
| Il wizard duplicava `/privacy` | `/privacy` resta la fonte completa, collegata dal passo | Una sola fonte legale da mantenere |
| Il nome ente poteva mancare | Usare `text_no_name` senza placeholder | Nessun dato tecnico esposto al cittadino |
| Policy non pubblicata | Conservare alert e consenso disabilitato | Non raccogliere dati senza informativa |

Il tema non duplica la copy: il contratto vive nel modulo Fixcity e Sixteen ne
verifica soltanto la resa visuale.

## Contratto visuale

- due paragrafi brevi `text-paragraph`;
- tre link localizzati per lingua: privacy, termini e cookie policy;
- checkbox vicino al link;
- nessun titolo, elenco o paragrafo della policy integrale nello step;
- struttura wizard, autenticazione, validazione e step successivi invariati.

## Acceptance criteria / gate ECC

- [x] policy completa non renderizzata nello step 1;
- [x] link e copy localizzati nelle quattro lingue;
- [x] fallback senza nome ente e alert di policy mancante coperti;
- [x] test Pest nel modulo Fixcity;
- [ ] screenshot autenticati a 375, 768 e 1440 px con account DCP-98;
- [ ] confronto DOM/CSS con il riferimento Design Comuni nei tre viewport.

Verifica applicativa:

```bash
cd laravel
php artisan test --filter='PrivacyStepIsATwoLayerNoticeTest|CreateTicketWizardWidget privacy step notice' Modules/Fixcity/tests
```

La verifica browser autenticata è ancora **non verificata**: senza account DCP-98
il browser anonimo viene correttamente reindirizzato a `/it/auth/login`.

## Fuori scope

Non si modifica `laravel/head`, il componente Cms, il layout Sixteen,
l'autenticazione o il contenuto della pagina CMS `/privacy`.
