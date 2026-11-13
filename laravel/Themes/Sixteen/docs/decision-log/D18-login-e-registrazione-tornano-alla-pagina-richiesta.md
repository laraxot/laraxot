---
id: D18
title: "Login e registrazione tornano alla pagina richiesta (url.intended)"
status: accepted
date: 2026-10-13
story: Modules/Fixcity/docs/stories/dcp-124-viaggio-cittadino-login-registrazione-ritorno.story.md
type: note
updated: 2026-10-13
tags: [docs, sixteen, decision-log]
qmd: "d18: login e registrazione tornano alla pagina richiesta sixteen"
---

# D18: login e registrazione tornano alla pagina richiesta

**Contesto.** Un ospite che apre una pagina protetta (es. `/it/tickets/create` dal pulsante «Segnala disservizio» dell'elenco) viene mandato al login con `url.intended` in sessione (`redirectGuestsTo` in `bootstrap/app.php`). Il `LoginWidget` lo ignorava (atterraggio sulla home) e l'azione di fine registrazione puntava a `route('dashboard')`, che nel front office e' un 404.

**Decisione.** Entrambi chiamano `redirectIntended(default: ...)`: pagina richiesta, altrimenti `route('dashboard')` se esiste, altrimenti `/{lingua}`.

**Alternative scartate.** (a) Atterrare sempre su `area-personale/pratiche`: obbliga chi voleva segnalare a un passo in piu' e l'area personale e' ancora in corso (DCP-52/106). (b) Creare una pagina `dashboard`: e' un template promozionale, non un bisogno del prodotto.

**Effetti.** Meno passaggi per chi segnala (GDPR art. 5(1)(c), art. 25); nessun cambio per chi accede direttamente (home). Limite: nessuna validazione che `url.intended` sia dello stesso host; Laravel salva solo URL della stessa applicazione (`redirect()->guest()` usa l'URL della richiesta), quindi il rischio di open redirect non e' introdotto qui.

**Verifica.** `Modules/Fixcity/tests/Feature/Auth/AuthReturnsToRequiredPageTest.php`, mutation check, Playwright su fixture isolata.
