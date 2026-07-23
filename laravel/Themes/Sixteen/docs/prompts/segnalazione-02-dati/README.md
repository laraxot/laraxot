# segnalazione-02-dati

Cartella di lavoro per la fase HTML parity di `segnalazione-02-dati`.

## Obiettivo
- Raggiungere almeno il `90%` di parity strutturale HTML rispetto alla reference Design Comuni.
- Usare solo la blade dinamica [`/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`](/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php).
- Salvare gli artifact di confronto in `body-structure-comparison/`.

## Guardrail permanenti
- Il tag `<body>` deve restare plain: solo `<body>`, senza classi o attributi di parity.
- Per questa route lo scoping CSS/JS corretto parte da `.page-content[data-slug="tests.segnalazione-02-dati"]`, perche `pages/tests/[slug].blade.php` ora usa il wrapper canonico del Cms.
- Lo stepper mobile/tablet della pagina deve replicare il reference con un solo step visibile + contatore `2/3`.
- I fix di visual parity vanno fatti in CSS/JS, non introducendo hook HTML extra nel body.

## Comando

```bash
bashscripts/html/html-structure-compare.sh \
  "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html" \
  "http://127.0.0.1:8000/it/tests/segnalazione-02-dati" \
  "segnalazione-02-dati" \
  "laravel/Themes/Sixteen/docs/prompts/segnalazione-02-dati/body-structure-comparison" \
  90
```

## Output
- `body-structure-comparison/report.md`
- `body-structure-comparison/summary.json`
- `body-structure-comparison/diff.txt`
- `body-structure-comparison/reference-body.html`
- `body-structure-comparison/local-body.html`
