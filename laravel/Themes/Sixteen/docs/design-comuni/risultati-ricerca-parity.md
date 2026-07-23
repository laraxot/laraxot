# Risultati Ricerca Parity Report

Data: 2026-04-03

## Scope

Route analizzata: `http://127.0.0.1:8000/it/tests/risultati-ricerca`
Riferimento: `https://italia.github.io/design-comuni-pagine-statiche/sito/risultati-ricerca.html`

File sorgente coinvolti:
- `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`
- `laravel/config/local/fixcity/database/content/pages/tests.risultati-ricerca.json`
- `laravel/Themes/Sixteen/resources/views/components/blocks/search/results.blade.php`
- `laravel/Themes/Sixteen/resources/views/components/blocks/pagination/default.blade.php`
- `laravel/Themes/Sixteen/resources/css/style-apply.css`

## Stato del gate strutturale

Esito corrente: la pagina non supera ancora il gate operativo del `90%` richiesto per avviare un pass affidabile solo CSS/JS.

Top-level `body` senza `script`:
- riferimento e locale coincidono a livello di shell: `skiplink`, `header`, `main`, `#search-modal`, `footer`
- il delta vero e dentro `main`

## Differenze strutturali verificate

Riferimento, figli diretti di `main`:
- `h1.visually-hidden#search-container`
- `div#main-container.container`
- `div.container`
- `div.container`
- `div.bg-primary`
- `div.bg-grey-card.shadow-contacts`
- `div.it-example-modal`

Locale, figli diretti di `main`:
- `div.cmp-breadcrumbs`
- `div.container`
- `div.container`
- `div.container.p-0`

Delta principali:
- manca `h1.visually-hidden#search-container`
- il breadcrumb locale non vive nel wrapper `div#main-container.container` del riferimento
- mancano i blocchi bassi `div.bg-primary`, `div.bg-grey-card.shadow-contacts` e `div.it-example-modal`
- il locale concentra risultati e filtri in tre container, ma non replica ancora l'intera shell del riferimento

## Differenze visive confermate da screenshot

Screenshot canonici:
- [reference-risultati-ricerca.png](./screenshots/risultati-ricerca-parity/reference-risultati-ricerca.png)
- [local-risultati-ricerca.png](./screenshots/risultati-ricerca-parity/local-risultati-ricerca.png)
- [analysis.md](./screenshots/risultati-ricerca-parity/analysis.md)

Visivamente il locale diverge su:
- search bar: label, input e bottone non hanno ancora il ritmo del riferimento
- breadcrumb e heading superiore: nel riferimento la query e dominante, nel locale il blocco alto resta piu compresso e frammentato
- listing card: card locali troppo grandi, con bordo scuro e gerarchia differente
- sidebar filtri: contenuto piu povero e densita diversa dal riferimento

## Conclusione operativa

La pagina `risultati-ricerca` e piu vicina della FAQ sul piano semantico, ma non e ancora alla soglia utile per dichiarare parity strutturale.

Quindi il prossimo step corretto e:
1. riallineare la shell HTML utile della pagina risultati ricerca
2. rifare il confronto body/main
3. solo dopo avviare il pass CSS/JS di parity visuale

## Tooling e riferimenti

- [Analisi storica precedente](./RISULTATI_RICERCA_ANALISI.md)
- [Screenshot folder](./screenshots/risultati-ricerca-parity/)
- [CMS note](../../../Modules/Cms/docs/design-comuni-risultati-ricerca.md)
