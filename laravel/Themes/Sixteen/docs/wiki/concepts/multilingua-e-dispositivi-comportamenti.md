---
title: "Multilingua e dispositivi: comportamenti osservati (it, en, de, es; 375, 768, 1024, 1440)"
type: concept
theme: Sixteen
tags: [i18n, multilingua, mobile, tablet, responsive, a11y, wizard, selettore-lingua]
created: 2026-10-13
updated: 2026-10-13
qmd: "multilingua dispositivi comportamenti selettore lingua fallback it hreflang canonical wizard step lingua cambio lingua touch target 44 menu utente tendina responsive 375 768"
status: draft
---

# Multilingua e dispositivi: comportamenti osservati

Misure fatte il 2026-10-13 sul sito di sviluppo (`http://192.168.1.40:8000`) con Playwright (Chromium, `hasTouch` e `isMobile` a 375 e 768), sessione autenticata per le pagine riservate. Dove una riga dice "verificato" c'e' una misura; dove dice "ipotesi" e' lettura del codice. Studio e difetti con priorita': story DCP-55 (`../../bmad/stories/dcp-55-multilingua-e-dispositivi.story.md`). Regole di traduzione e glossario: `../../i18n/00-comportamento-multilingua.md`.

Avvertenza sul metodo: nella stessa ora altri agenti hanno tradotto header, footer e home in de/es e localizzato i link. Le misure portano l'orario; il quadro finale e' quello delle 17:45 UTC.

## Come si sceglie la lingua

- Lingue servite (`config/laravellocalization.php`): `it`, `en`, `de`, `es`. Predefinita `app.locale` e `fallback_locale` = `it`.
- La lingua viene SOLO dal primo segmento dell'URL (`/it/`, `/en/`, `/de/`, `/es/`). Middleware `SetFolioLocale` (`Modules/Cms/app/Http/Middleware/SetFolioLocale.php`): prefisso valido, altrimenti `lang` salvata dell'utente, altrimenti `it`.
- Verificato: `/` risponde 302 verso `/it` con qualunque `Accept-Language` (de, es, en-GB, fr) e anche dopo aver visitato `/en/` nella stessa sessione. `useAcceptLanguageHeader` e `LocaleSessionRedirect` risultano senza effetto.
- Verificato: un URL senza prefisso (`/tickets`) risponde 404; un prefisso sconosciuto (`/fr/`, `/en-GB/`) risponde 404; `/IT/` risponde 200 con `lang="it"` (stesso contenuto di `/it/`).
- Verificato: nessun cookie di lingua; il cookie di sessione non ricorda la scelta.
- Verificato: un ospite su `/{lang}/tickets/create` va a `/{lang}/auth/login` (la lingua si conserva nel reindirizzamento, `bootstrap/app.php` `redirectGuestsTo`).

## Dichiarazione della lingua nella pagina

- `<html lang>` e' corretto su 40/40 pagine misurate (`it`, `en`, `de`, `es`), da `Themes/Sixteen/resources/views/components/layouts/main.blade.php:11`.
- Verificato: nessuna pagina ha `<link rel="alternate" hreflang>`, `<link rel="canonical">`, `og:locale` o `og:title`. Esiste un componente completo in `Modules/Cms/resources/views/components/metatags.blade.php` che il layout non usa. L'head del layout ha solo charset, viewport, title, description.
- `<meta name="viewport" content="width=device-width, initial-scale=1.0">`: lo zoom e' consentito (niente `user-scalable=no` ne' `maximum-scale`).
- Il `<title>` e' tradotto sulla home, ma resta generico "FixCity" su molte pagine riservate e in de/es.

## Selettore di lingua dell'header

- Elementi: `button[aria-controls=languages]` e `.dropdown-menu` fratello; voci `ITA`, `ENG`, `DEU`, `SPA` (codici a tre lettere dal JSON `sections/header.json`, `languages.items`). Il pulsante riporta "Lingua attiva: ITA" nella lingua corrente.
- Verificato: ogni voce porta alla STESSA pagina nell'altra lingua (`/it/tickets` -> `/en/tickets`), sostituendo il primo segmento (`localeUrl` in `Themes/Sixteen/resources/views/components/sections/header/design-comuni.blade.php:21-30`). Provato da `/it/`, `/it/tickets`, `/it/tickets/create`, `/it/area-personale/pratiche`, `/it/auth/login`.
- Verificato: la query string NON viene mantenuta. `/it/tickets?q=strada&page=2` diventa `/en/tickets`; `/it/tickets/create?step=...` diventa `/en/tickets/create`.
- Con tocco: si apre, si chiude con un tocco fuori e con Esc, il fuoco resta sul pulsante.
- Le voci non sono tradotte per pagina: un cambio di lingua e' un normale caricamento di pagina.

## Ripiego sull'italiano

`fallback_locale = it`: una chiave mancante in en/de/es mostra l'italiano senza errore. Il confronto con la stessa pagina in italiano trova i testi non tradotti (`bashscripts/tools/i18n/audit-locales.py`). Alle 17:05 de/es erano quasi interamente italiani (97-101 testi per pagina); alle 17:45 restano gli elementi in tabella nella story (wizard, login/register, etichette della mappa). Chiavi grezze visibili nel testo: zero in tutte le lingue. In `it` si vede `filament-forms::components.select.search_label` come valore di un attributo del campo Categoria (manca la voce nella traduzione italiana di Filament).

## Il wizard di segnalazione e il cambio lingua

- Il wizard (`/{lang}/tickets/create`) e' un wizard Filament con `isStepPersistedInQueryString: true`. La chiave del passo nasce dall'ETICHETTA tradotta (`BuildTicketReviewSummaryAction::dataStepKey()`, `Modules/Fixcity/app/Actions/BuildTicketReviewSummaryAction.php`, circa riga 218). Verificato:

  | Lingua | Passo 1 | Passo 2 | Passo 3 |
  |---|---|---|---|
  | it, de, es | `form.autorizzazioni-e-condizioni::data::wizard-step` | `form.dati-di-segnalazione::data::wizard-step` | `form.riepilogo::data::wizard-step` |
  | en | `form.privacy-and-terms::data::wizard-step` | `form.report-details::data::wizard-step` | `form.summary::data::wizard-step` |

- Verificato: `?step=<chiave valida per la lingua>` apre direttamente quel passo (anche il 2 e il 3, senza passare dal 1). Con la chiave di un'altra lingua il wizard apre il passo 1 senza avviso e lascia la chiave sbagliata nell'URL.
- Verificato: cambiando lingua dal passo 2 con un campo compilato (nessun invio) si torna al passo 1 di `/{nuova lingua}/tickets/create`, tutti i campi vuoti, nessun avviso di uscita (`beforeunload`), nulla in `localStorage` o `sessionStorage`. Con "Indietro" del browser il passo 2 si riapre dall'URL, campi vuoti.
- Conseguenza: la lingua si cambia a inizio pratica, non a meta'. Uno stato condiviso fra lingue non esiste.

## Link interni e lingua

- Il logo e il link alla home usano `url('/')` (`design-comuni.blade.php:129` e `:168`): da `/en/` portano a `/` e quindi a `/it`.
- A 17:48 i CTA della home puntano a `/{lang}/tickets/...` (alle 17:25 erano cablati `/it/`, poi corretti da altro agente). La mappa pubblica usa `/api/tickets/geojson` senza prefisso: l'azione `BuildTicketsGeoJsonAction` (riga 99) usa `app()->getLocale()` che li' e' `it`, quindi etichette di stato e tipo italiane e `detail_url` `/it/tickets/{id}` in ogni lingua (verificato con `Accept-Language: en`).
- Verificato: 18 dei 28 link interni distinti della home danno 404 in tutte le lingue (voci di menu e piede dalle pagine di prova del riferimento): non esistono pagine di destinazione.

## Date e numeri (verificato con tinker; nessuna pagina misurata ha date)

- Il dettaglio segnalazione e l'elenco pubblico non mostrano date; l'elenco "Le mie segnalazioni" era vuoto per l'utente di prova.
- `isoFormat('LL')` (`BuildPersonalAreaTicketRowAction.php:60`) e' corretto: `3 ottobre 2026`, `October 3, 2026`, `3. Oktober 2026`, `3 de octubre de 2026`.
- `translatedFormat('d F Y')` (`Modules/User/resources/views/pages/area-personale/pratiche.blade.php:59`) da `03 ottobre 2026`, `03 Oktober 2026`, `03 octubre 2026`: giorno con zero iniziale, senza il punto tedesco ne' il "de" spagnolo.
- `number_format(1234567.891, 2)` da `1,234,567.89` in tutte le lingue; `Number::format(..., locale: $l)` da `1.234.567,89` (it, de, es) e `1,234,567.89` (en). Usi di `number_format` senza lingua: `ticket-list/results-header.blade.php:15`, `segnalazioni-elenco/results-header.blade.php`.
- La chiave `__('Inviata il')` restituisce se stessa in en, de, es.
- `trans_choice` non e' usato sui conteggi dei risultati (`:count Risultati` e' una frase unica): ipotesi, "1 Risultati" con un solo risultato.

## Dispositivi

Larghezze: 375x800 e 768x1024 con tocco, 1024x768 e 1440x900. Verificato su 10 pagine (home, elenco, wizard passi 1-3, login, register, pratiche, impostazioni, profile) in italiano; a 375 anche in en, de, es con risultato identico.

- Nessun overflow orizzontale (`scrollWidth - clientWidth` = 0) e nessun elemento oltre il bordo destro, in nessuna lingua. Nessuna immagine o tabella piu' larga del contenitore (le tessere Leaflet sono tagliate dalla mappa).
- Nessun campo di testo sotto 16 px (niente zoom automatico su iOS). Campi alti 37 px.
- Bersagli tattili sotto 44x44 (esclusi i link in linea): a 375 da 45 a 59 elementi per pagina, di cui 43 sono i link del piede (24 px di altezza); a 768 da 36 a 51 (34 nel piede). Sotto i 24 px (WCAG 2.2 AA, 2.5.8): il collegamento "Leaflet" (51x14), il link regione (21 px), le icone social del piede (24) e a 1024/1440 le icone social dell'header (16x16).
- Due varianti di header convivono. Pagine con `.page-content[data-dc]` (elenco, wizard, pratiche, impostazioni): hamburger 24x24, pulsante lingua 73x36, pulsante utente 37x36. Altre pagine (home, login, register, profile): hamburger 48x48, menu a tendina entro lo schermo, ma l'hamburger si sovrappone al link regione (24-143 contro 8-56 px).
- Hamburger con tocco: si apre a tutto schermo, `aria-expanded=true`, il fuoco va su "Nascondi la navigazione" (44x44), Esc chiude. Modale di ricerca: a tutto schermo, `aria-modal=true`, Esc chiude.
- Menu a tendina sulle pagine `data-dc`: `placeDropdownMenu` (`Themes/Sixteen/resources/js/app.js:189-195`) allinea il menu a sinistra del pulsante senza controllare il bordo destro. Menu utente (228 px): tagliato di 169 px a 375 e 147 px a 768, restano visibili 59 px; menu lingua (160 px): tagliato di 28 px a 375 e 6 a 768. Voci della lingua 160x40, dell'utente 228x56.
- L'iniziale dell'utente e' bianca su cerchio bianco (`color rgb(255,255,255)`): `13-final-runtime-overrides.css:7-47` colora `> a.btn...` ma l'elemento e' un `button#header-user-toggle`.
- Posizione fissa o sticky: solo la barra Debugbar (ambiente di sviluppo) e `div.cmp-navscroll.sticky-top` in impostazioni (5% dello schermo). Nessun banner cookie visibile.
- A 375 su `/auth/login` il campo password e' largo 141 px su 291 perche' il pulsante "Mostra password" gli sta accanto.

## Come ripetere le misure

Strumenti nel scratchpad della sessione non versionati; i comandi equivalenti: `python3 bashscripts/tools/i18n/audit-locales.py /auth/login /tickets ""` per il testo, Playwright con `isMobile` per le misure di layout. Bloccare gli host esterni (tessere mappa, font) velocizza le prove: senza, `networkidle` aspetta fino a 60 secondi.
