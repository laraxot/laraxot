---
id: dcp-449
title: "DCP-449: salto di layout del wizard /tickets/create (CLS 0,44 desktop, 0,49 mobile): barra cookie in flusso e contenuto nascosto fino all'idratazione"
type: story
status: open
module: Sixteen
created: 2026-11-12
updated: 2026-11-12
tags: [bmad, story, sixteen, wizard, cls, cookie, gdpr, performance, ux, tickets]
qmd: "salto di layout wizard tickets create CLS 0,44 0,49 barra cookie data-layout flow place() x-cloak idratazione 155 px footer 584 a 0 WCAG 2.4.11"
related:
  - ./front-office-production-readiness-audit.story.md
  - ./dcp-450-peso-e-cache-degli-asset-del-tema.story.md
  - ../../../../Modules/Gdpr/docs/stories/dcp-445-guardia-del-cablaggio-del-banner-cookie.story.md
  - ../../../../Modules/Fixcity/docs/stories/dcp-457-guardia-e-misure-del-primo-livello-informativa-passo-1.story.md
  - ../../../../Modules/Fixcity/docs/stories/dcp-200-registro-unico-delle-decisioni-aperte.story.md
issues: ["https://github.com/laraxot/theme_sixteen_fila5/issues/101"]
discussions: ["https://github.com/laraxot/theme_sixteen_fila5/discussions/94", "https://github.com/laraxot/theme_sixteen_fila5/discussions/110"]
---

# DCP-449: salto di layout del wizard `/tickets/create`

Status: **aperta** (2026-11-12). Fase BMAD: Analysis fatta (audit UX-02, riprodotto a 390 px); Solution da decidere perché la causa sta in due moduli. Nessuna modifica fatta.

## Cosa si misura (audit, 2026-11-11, dev `:8081`, da loggato)

CLS **0,44 desktop, 0,49 mobile** (soglia «buono» ≤ 0,1, proposta di riferimento non ancora adottata dal progetto). Misurato da me a 390 px: due shift.

| Shift | Causa verificata |
|---|---|
| 0,184 a 0,7 s | la barra cookie passa in modalità `data-layout="flow"` (`position: static` in cima, 155 px) decisa dallo script `place()` di `Modules/Gdpr/resources/views/cookie-consent/index.blade.php` quando la barra fissa coprirebbe un controllo del primo schermo: spinge giù header e main. Su `/it` è `fixed` e non c'è salto |
| 0,308 a 1,4 s | il contenuto del wizard è nascosto con `x-cloak`/`hidden` fino all'idratazione (il footer passa da 584 a 0) |

## Osservazione aggiunta (DCP-457, misure «in lavorazione» di un'altra sessione, da rifare al «pronto»)

Sul passo 1 la barra cookie sposta il contenuto di circa 150 px; a 375 px in de/es la casella di consenso e «Avanti» restano sotto la piega, in it/en solo dopo aver chiuso la barra. Non è un secondo difetto: è lo stesso salto visto da chi deve premere «Avanti».

## Perché non l'ho corretto

La barra in flusso è una **scelta di progetto del modulo Gdpr** (evita di coprire i controlli, WCAG 2.4.11), e il rendering del wizard è lato client. Correggere vuol dire toccare due moduli
e decidere un comportamento: non è un fix da una riga.

## Opzioni

| Opzione | Cosa cambia | Rischio |
|---|---|---|
| (a) Decidere `place()` **prima del primo paint** (script sincrono in `<head>`/inizio body) e dare altezza minima o render server allo step attivo | niente salto della barra né del wizard | tocca Gdpr e il wizard; lo script sincrono ritarda il primo paint di pochissimo |
| (b) Barra sempre `fixed` con spazio riservato (`scroll-padding`/padding in fondo) | niente salto, nessuna decisione al caricamento | può coprire un controllo (2.4.11): serve la prova col browser |
| (c) Accettare il salto, ridurlo (altezza fissa del segnaposto) | nessun lavoro grosso | il CLS resta alto: non risolve |

Proposta: (a), con prova a 390 px prima e dopo. Non è una decisione mia: tocca il comportamento del banner (DCP-445 ne ha messo la guardia).

## Criteri di accettazione (quando si lavora)

- [ ] Decisione scritta (a/b/c) nel registro DCP-200 e nella story di Gdpr.
- [ ] CLS rimisurato a 390 e 1440 px, **a macchina libera** (le misure di tempo di un giro con carico 30-40 non valgono, F-05): confronto prima/dopo con la stessa tecnica.
- [ ] Nessun controllo del primo schermo coperto dalla barra (2.4.11).
- [ ] Checkbox e «Avanti» del passo 1 raggiungibili senza scorrere a 375 px, oppure scorrimento accettato per iscritto (vedi DCP-457).
- [ ] Una spec Playwright che fallisce se il CLS del wizard supera la soglia scelta.

## Verifica

Nessuna correzione in questa story; le cifre sono quelle dell'audit dell'11 novembre.
