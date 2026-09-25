# SVG Icon Custom — Regola

## Pattern

Per aggiungere un'icona custom in Filament, segue il pattern:

```
Icon name:    xot-files.{ext}
SVG path:     laravel/Modules/Xot/resources/svg/files/{ext}.svg
Risoluzione:  BladeUI Icons via XotBaseServiceProvider::registerBladeIcons()
              prefix "xot" → icona xot::files.{ext}
```

## Esempi

| Azione | Icon name | SVG |
|--------|-----------|-----|
| ExportPdfAction / PdfAction | `xot-files.pdf` | `resources/svg/files/pdf.svg` |
| ExportXlsAction / ExportXlsLazyAction / ExportTreeXlsAction / ExportXlsTableAction | `xot-files.xls` | `resources/svg/files/xls.svg` |
| ExportXlsxAction | `xot-files.xlsx` | `resources/svg/files/xlsx.svg` |

Mai `->icon(__('…'))` per il nome icona: se la chiave manca, Blade riceve
la stringa della chiave e l'icona si rompe. Hardcodare `xot-files.{ext}`.

## Come funziona

1. `XotBaseServiceProvider::registerBladeIcons()` registra il prefix `xot` con path `resources/svg/`
2. Blade Icons risolve `xot-files.pdf` cercando `resources/svg/files/pdf.svg`
3. In Filament: `->icon('xot-files.pdf')`

## Aggiungere una nuova icona custom

1. Creare `laravel/Modules/Xot/resources/svg/files/{nome}.svg`
2. Usare `->icon('xot-files.{nome}')` nel codice Filament
3. Assicurarsi che lo SVG sia valido XML (verificare con `xmllint --noout`)

## SVG consigliati

- Stile outline 24×24 con `stroke="currentColor"` (coerente con Heroicons)
- `aria-hidden="true"` e `role="img"` per accessibilità
- Nessun `stroke-width` eccessivo per scalabilità
- **Niente `<text>` microscopico** (es. "XLSX" a 4px): a toolbar size è
  illeggibile. Preferire metafore geometriche (griglia = foglio di calcolo,
  badge path = PDF). Story: `docs/bmad/stories/5.222-export-xlsx-grid-icon-ux.story.md`

## Distinzione export

| Ext | Metafora |
|-----|----------|
| `xlsx` | foglio + barra header piena + griglia 3 colonne |
| `xls` | foglio + griglia 2 colonne (senza header pieno) |
| `pdf` | foglio + monogramma path (no `<text>`) |

---

**Regola documentata**: 23 set 2026 — story BMAD `5.221` + `5.222`

## Pattern di design (story 5.223)

Un'icona **action** deve dire *cosa* E *cosa fa*: due cluster visivi dentro
il foglio+piega comune — **identita' formato a sinistra** (header+griglia
xlsx / griglia xls / glifo "P" pdf) + **freccia export a destra** (semantica
`document-arrow-down`: il dato esce dal file). Solo path, mai `<text>`:
a 16-24px il testo e' illeggibile (regola 5.222).

## Consolidamento fleet (story 5.228)

La regola "icona action = cosa + cosa fa" vale per **ogni** action che
produce un file, non solo `ExportPdf/Xls/XlsxAction`: PDF → `xot-files.pdf`,
XLS → `xot-files.xls`, XLSX → `xot-files.xlsx`.

Applicata a: `RecordPdfAction`, `MakePdfAction` (Ptv/Progressioni),
`GeneratePDF*Action` (Incentivi), `ExportButton` (Xot), chiavi lang
`actions.*.icon`/`navigation.icon` per pdf/xls/xlsx, descrittori formato
download (Activity). Escluse: import (freccia inversa), engine enum,
navigation di gestione, zip, log, download di tipo indeterminato.

Guard-test per modulo: `tests/Unit/Filament/PdfActionIconsTest.php`
asserisce `getIcon() === 'xot-files.pdf'` sulle action live.
