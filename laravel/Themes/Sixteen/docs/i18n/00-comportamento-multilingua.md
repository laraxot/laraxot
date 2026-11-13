---
title: "Comportamento multilingua del sito"
type: reference
theme: Sixteen
story: dcp-120
created: 2026-10-13
updated: 2026-10-13
qmd: "multilingua lingua locale it en de es fallback_locale SetFolioLocale hreflang html lang selettore lingua link localizzati wcag 3.1.1 3.1.2"
status: draft
tags: [docs, sixteen, i18n]
---

# Comportamento multilingua del sito

Mappa di come il sito sceglie, dichiara e mantiene la lingua. Ogni riga e' stata provata il 2026-10-13 su `http://192.168.1.40:8000` (curl, Playwright a 375 px, tinker) o letta nel codice con file e riga. Quello che non e' stato provato e' scritto come tale.

## Lingue servite

`laravel/config/laravellocalization.php` (righe 39 e seguenti) serve quattro lingue: `de`, `en`, `es`, `it`. Tutte da sinistra a destra (nessuna `dir`, nessuna lingua RTL servita). `hideDefaultLocaleInURL` e' `false` (riga 330): l'italiano ha il prefisso `/it` come le altre. `useAcceptLanguageHeader` e' `true` (riga 313) ma non ha effetto sulla radice, vedi sotto. La lingua di default e il fallback sono `it` (`laravel/config/app.php` righe 81 e 83).

## Come viene scelta la lingua

| Situazione | Esito misurato | Dove nasce |
|---|---|---|
| `/de/tickets`, `/es`, `/en/...` | pagina nella lingua del prefisso, `<html lang>` uguale al prefisso | Folio montato una volta per lingua: `Modules/Cms/app/Providers/FolioVoltServiceProvider.php` righe 117 e 157, middleware `SetFolioLocale` |
| `/` | 302 verso `/it`, anche con `Accept-Language: de` e `fr-FR` | `Modules/Cms/routes/web.php` righe 16-17: `redirect('/'.app()->getLocale())`, cioe' la lingua di default |
| `/tickets` (senza prefisso) | 404 | non esiste un montaggio senza prefisso |
| `/fr`, `/fr/tickets` (lingua non servita) | 404 | nessun montaggio per `fr` |
| `/DE` (maiuscolo) | 200, pagina in italiano con `<html lang="it">` | Folio accetta il segmento, `SetFolioLocale` confronta in modo esatto e ricade sul default |
| Utente autenticato con campo `lang` | il prefisso vince sempre; `lang` conta solo senza prefisso (caso che oggi non si verifica) | `Modules/Cms/app/Http/Middleware/SetFolioLocale.php` righe 29-48; il commento di classe elenca la priorita' al contrario del codice |
| Accesso a una pagina protetta da ospite | redirect al login nella lingua della richiesta | `laravel/bootstrap/app.php` righe 30-45 |

`SetFolioLocale` imposta sia `app()->setLocale()` sia `LaravelLocalization::setLocale()` (righe 51-58): senza la seconda, `getLocalizedURL()` darebbe sempre `it`. Carbon segue `app()->setLocale()` (provato: in `de` `translatedFormat('l j F Y')` da "Dienstag 13 Oktober 2026").

## Fallback: cosa succede quando manca una traduzione

Il fallback e' `it` (provato). Per i contenuti CMS `Modules/Cms/app/Models/Traits/HasBlocks.php` (`getBlocks`) legge `getTranslation` del campo e Spatie ricade sull'italiano: una pagina o una sezione senza il blocco `de` mostrava il testo italiano sotto `<html lang="de">`, senza errore. Prima di DCP-120 era il caso di header, footer, modale di ricerca e home: 114 righe visibili su 4 pagine uguali all'italiano in `/de` (audit-locales).

Stato dopo DCP-120: header, footer, modale di ricerca e home hanno i blocchi `de` ed `es` (e il test `ContentLocaleParityTest` lo impone). Restano in fallback italiano le 88 pagine `tests.*` (hanno solo `it` ed `en`) e le pagine che altri agenti stanno scrivendo. Il fallback delle chiavi `__()` dei file lang e' lo stesso: una chiave mancante in `de` o `es` mostra l'italiano.

## Selettore lingua

Nell'header, `config/local/fixcity/database/content/sections/header.json` chiave `languages.items`, resa da `Themes/Sixteen/resources/views/components/sections/header/v1.blade.php` → `tailwind.blade.php`.

- Prima di DCP-120 elencava solo `it` ed `en`. Dal codice (riga 53, `firstWhere('locale', $locale)['label'] ?? ''`) un `/de` aveva il pulsante senza etichetta. Non osservato, dedotto dal codice.
- Ora elenca `ITA`, `ENG`, `DEU`, `SPA` in tutti e quattro i blocchi (guardia: il test controlla che l'elenco coincida con le lingue servite).
- Il link cambia solo il primo segmento: `/it/tests/x` diventa `/de/tests/x` (righe 21-31). La query string si perde perche' viene usato `request()->path()`.
- I link del selettore non hanno `lang` ne' `hreflang`; le etichette sono codici di tre lettere senza il nome della lingua. Da migliorare, vedi DCP-123.

## Metadati e dichiarazione della lingua

| Elemento | Stato misurato | Dove |
|---|---|---|
| `<html lang>` | uguale al prefisso (`str_replace('_','-', app()->getLocale())`) | `Themes/Sixteen/resources/views/components/layouts/main.blade.php` riga 11 |
| `<title>` home | it, en, de, es propri (prima de/es: solo `FixCity`) | `pages/index.blade.php` riga 31, dati in `pages/home.json` chiave `title` |
| `<meta description>` home | it, en, de, es propri (prima de/es: testo italiano) | `pages/index.blade.php` righe 33-34, `home.json` chiave `description` (oggetto per lingua letto come attributo grezzo) |
| `hreflang` e `canonical` | assenti in tutte e quattro le lingue (0 occorrenze nell'HTML della home) | `main.blade.php`, nessuna emissione |
| `lang` sulle parti in altra lingua | assente: contenuto in fallback italiano resta sotto `lang="de"` | vedi DCP-123 |
| Testo dentro immagini | il logo del finanziamento europeo del footer ha testo italiano ("Finanziato dall'Unione europea") | immagine in `design-comuni/assets` |
| Titolo e descrizione di altre pagine | `/it/tickets`: la `<meta description>` contiene il JSON grezzo `{"it": ..., "en": ...}` in tutte le lingue; titolo `FixCity` in de/es | `pages/[container0]/index.blade.php` riga 58 (`getAttribute('description')` senza la decodifica usata nella home) |

Norme: la lingua della pagina (WCAG 3.1.1) e delle parti (3.1.2) sono criteri di successo WCAG 2.1 livello A e AA. Fino a quando i contenuti sono in fallback la pagina dichiara una lingua che non corrisponde al testo.

## Link interni

- I link del chrome (`url` in header, footer, modale) sono resi cosi' come sono scritti nel JSON, senza prefisso lingua. L'azione `Modules/Cms/app/Actions/ResolveLocalizedBlockDataAction.php` che li localizzerebbe e' chiamata solo dal componente `Modules/UI` (`Render\Block`), non dal percorso `x-section` / `x-page` del tema.
- Misurato in `/de`: `/regione`, `/amministrazione`, `/privacy`... restano percorsi senza prefisso. Quasi tutti rispondono 404 sia con sia senza prefisso (sono segnaposto del template). Due esistono davvero e sono stati corretti da DCP-120: `/tickets/create` (senza prefisso era 404 per tutti, italiano compreso) e `/domande-frequenti` (200 solo con prefisso).
- `route('container0.view', ...)` e `route('tickets.track')` restituiscono sempre `/it/...`, anche con locale `de` (provato con tinker): le rotte Folio hanno lo stesso nome in tutte le lingue e vince l'ultima registrata, `it`. Nella home questo mandava un cittadino tedesco dal pulsante "Meldung senden" alla pagina italiana. DCP-120 ha sostituito le chiamate in `home.json` con `LaravelLocalization::localizeURL('/tickets/create')` (alias disponibile in `Blade::render`): ora `/de` porta a `/de/tickets/create`. Resta aperta la causa (nome delle rotte), vedi DCP-123.
- Il prefisso lingua e' una scelta di URL: gli slug delle pagine non si traducono (`/de/tickets`, non `/de/meldungen`). Per questo gli URL di `en` in header e footer, che erano tradotti (`/administration`, `/news`), sono stati riallineati a quelli italiani: un URL tradotto non puo' corrispondere a nessuna pagina, la pagina ha un solo slug.

## Persistenza della scelta

Nessuna. Provato: dopo aver aperto `/de` con il cookie di sessione, `/` rimanda ancora a `/it`; non esiste un cookie lingua. L'unica memoria e' il prefisso nell'URL. `LocaleSessionRedirect` e' nella catena dei middleware ma non agisce perche' tutte le pagine Folio hanno il prefisso.

## Date, numeri e valute

Lo stato e' misto e solo in parte dipende dalla lingua:

- Carbon: segue la lingua (`translatedFormat`). Ma molte viste usano un formato fisso `d/m/Y`: `Themes/Sixteen/resources/views/components/ticket-card.blade.php:21`, `components/data-display/table.blade.php:198-199`, `components/forms/date-picker.blade.php:461-473`. Un lettore tedesco si aspetta `13.10.2026`.
- `number_format()` non e' sensibile alla lingua (provato: in `de` da `1,234.5`): `components/blocks/ticket-list/results-header.blade.php:15`, `components/data-display/table.blade.php:200-201`. `Illuminate\Support\Number::format` non usa la lingua dell'app senza `Number::useLocale` (provato).
- `components/utilities/pagopa-button.blade.php:38` ha il formato italiano scritto nel codice (`,` decimale, `.` migliaia).
- `components/municipal/service-card.blade.php:121` forza `->locale('it')` sulle scadenze.
- Fuso: `Europe/Berlin` come valore di riserva in `Modules/Xot/app/Providers/XotServiceProvider.php` righe 108-111, il valore reale viene da `config('app.timezone')`.

## Contenuti scritti dai cittadini

Il testo di una segnalazione e' nella lingua dell'autore e non si traduce: va marcato con la lingua dell'autore se la si conosce (oggi non e' salvata). Titoli, indirizzi e descrizioni dei ticket di esempio nell'elenco `/tickets` sono italiani in tutte le lingue per questo motivo.

## Come verificare

```bash
BASE=http://192.168.1.40:8000 python3 bashscripts/tools/i18n/audit-locales.py "" /tickets /auth/login
cd laravel && APP_ENV=testing DB_CONNECTION=sqlite ./vendor/bin/pest Modules/Fixcity/tests/Unit/ContentLocaleParityTest.php
```

Le "chiavi grezze" riportate dall'audit sono commenti HTML delle viste, non chiavi mancanti. Glossario e scelte di traduzione: [01-glossario.md](01-glossario.md). Story: `bmad/stories/dcp-120-multilingua-comportamenti-e-copertura.story.md`.
