---
id: dcp-447
title: "DCP-447: il tema torna ricostruibile e ogni difetto portato dai mirror ha la sua guardia (13 PHP rotti, file importati cancellati, nome Volt doppio)"
type: story
status: done
module: Sixteen
created: 2026-11-12
updated: 2026-11-12
tags: [bmad, story, sixteen, mirror, sync, regressione, guardie, php-l, vite, volt, wizard]
qmd: "tema ricostruibile mirror 6cb55f0d php -l 13 file corrotti ThemeServiceProvider SpidAuthAction npm run build import mancanti WizardIncludesResolveTest PartsIncludesResolveTest PhpSourcesCompileTest VoltFragmentNamesAreUniqueTest pages-old.view"
related:
  - ./front-office-production-readiness-audit.story.md
  - ./auth-components-regression-restore.story.md
  - ./dcp-453-ritiro-del-codice-morto-del-tema-decisione.story.md
  - ./dcp-451-guardia-sui-riferimenti-a-viste-cancellate.story.md
  - ../bmad/decisions/contract-tests-follow-live-files.md
  - ../../../../Modules/Fixcity/docs/stories/dcp-200-registro-unico-delle-decisioni-aperte.story.md
issues: ["https://github.com/laraxot/theme_sixteen_fila5/issues/99"]
discussions: ["https://github.com/laraxot/theme_sixteen_fila5/discussions/94"]
---

# DCP-447: il tema torna ricostruibile e ogni difetto dei mirror ha la sua guardia

Status: **fatta** (2026-11-12) per quanto riguarda il tema; resta **aperto fuori dal tema** il remoto a monte (sezione finale). Fasi BMAD: Analysis (audit L-05, L-08, L-15),
Solution (ripristino in avanti), Verification (guardie con prova inversa). Il dettaglio riga per riga è nell'audit; questa story è il documento unico del filone.

## Perché esiste

Il mirror «.» `6cb55f0d` del tema (sync dell'altra macchina) ha prodotto tre famiglie di guasti che nessun test segnalava finché non si apriva la pagina o non si lanciava il build:

| Famiglia | Cosa si era rotto | Come l'ho misurato |
|---|---|---|
| Sorgenti PHP corrotti (L-05) | 13 file non compilano (`ThemeServiceProvider`, `SpidAuthAction`, `routes/auth.php`, `SixteenComposer`, `CreateAppointment`, `Appointment`, 7 `Models/Municipal/*`): riga vecchia e riga nuova incollate insieme, `use` doppi | `php -l` su 481 file; `phpstan analyse Themes/Sixteen` dava 184 errori di parsing, `AppointmentEnumsTest` andava in ParseError |
| File ancora importati, cancellati (L-08) | `npm run build` non partiva (5 JS e 9 CSS importati e spariti) e il riepilogo del wizard dava 500 «View not found» a ogni utente loggato (`report-partials/images-preview.blade.php`) | build rosso; vista mancante incluso da `summary-sections.blade.php:26` |
| Nome Volt doppio (L-15 b) | `pages/tests/pages-view-reference.blade.php` aggiunto dal mirror con `@volt('pages.view')`, lo stesso nome di `pages_old/pages/[slug]` e `pages_old1/pages/[slug]`: Volt scrive il frammento in `storage/framework/views/<md5 del solo nome>` e una compilazione completa mette per ultimo `pages_old`, quindi la pagina viva perde il frammento (`data-dc`) | test `VoltFragmentNamesAreUniqueTest` rosso |

## Cosa è stato fatto (tutto in avanti, nessun revert)

- **L-05**: i 13 file sono stati riscritti dall'**ultima revisione che compila**, file per file (`006ca426` per 6, `1c4bda3a` per 5, `bc1ec833` per 2). `6cb55f0d^` non era una buona sorgente (3 dei 13 non compilano nemmeno lì).
  Riapplicato il gate SPID `digitalIdentityRoutesEnabled` che la revisione vecchia non aveva (P0 del `production-hardening-p0`).
- **L-08**: i 5 JS, i 9 CSS e il partial delle anteprime sono stati riscritti da `6cb55f0d^`, con i conteggi di riga uguali alle statistiche del commit (la revisione `a50c38ec` era sbagliata: 206 righe contro 249 cancellate). Gli altri file cancellati (`bootstrap-italia.js`,
  `camera-upload.js`, `svg/`, vecchi CSS) non sono referenziati e restano cancellati.
- **L-15 (b)**: i due frammenti Volt dei file morti sono stati rinominati `pages-old.view` e `pages-old1.view` (stessa convenzione `pages-old.*` di `0b1a4486`); nessun file cancellato.

## Le guardie (rompono quando il difetto torna)

| Guardia | Cosa impone | Prova inversa dichiarata nell'audit |
|---|---|---|
| `tests/Unit/PhpSourcesCompileTest.php` | `php -l` su `app`, `routes`, `config`, `http`, `src` | reinserito un file rotto: rosso con l'errore `Cannot use … already in use` che il solo parser non vede |
| `tests/Unit/WizardIncludesResolveTest.php` | ogni `@include` del wizard, anche con prefisso `$variabile.`, punta a un file esistente; prefisso non risolvibile = rosso | rossa senza il partial |
| `tests/Unit/PartsIncludesResolveTest.php` | ogni `@include($var.'.nome')` con `$var` assegnata a `pub_theme::cartella` trova il file (la variabile si cerca nel file, poi nelle cartelle antenate) | presente nel tema; la sua prova inversa non è documentata nell'audit: **da rifare se la si modifica** |
| `tests/Unit/VoltFragmentNamesAreUniqueTest.php` | nessun nome `@volt('…')` ripetuto fra le viste | rosso col `pages.view` doppio |

Sorella già esistente: `DesignComuniIconSpriteIsDeployableTest` (F-01/F-02: sprite icone che `deploy.sh` deve trovare), descritta in `icon-sprite-deployability.story.md`.

## Limiti dichiarati

- Le guardie dei partial non coprono gli include col **nome composto a runtime** (`…layout-'.$d('layout')`): si provano con la scansione HTTP. È la lacuna che DCP-451 vuole stringere.
- I conteggi (481 file, 184 errori, 13 file) sono quelli dell'11 novembre. Oggi non ho rilanciato `php -l` sull'intero tema.

## Resta aperto fuori dal tema

- Il remoto `laraxot/theme_sixteen_fila5` ha ancora i file rotti e ogni mirror li riporta: va corretto a monte, e `php -l` va messo **prima** di ogni mirror. Non è nelle mie mani (remoto del tema) e nulla è committato.
- Decisione sul codice morto che resta (18 rossi di `ComuneControllerTest`, `pages_old*`, `header-comune`, `FrontofficeSearchSuggestions`): DCP-453, DEC-70.
- Lezione di metodo: una story `done` non protegge da una regressione che arriva da un'altra parte; serve una guardia che si rompa al ritorno del difetto (L-05). Una story è anche `done` solo con una prova.

## Criteri di accettazione (verificati l'11 novembre, da rifare prima di un go-live)

- [x] `php -l` 0 rotti su 481; PHPStan del tema senza errori di parsing.
- [x] `npm run build` verde, manifest ricostruito.
- [x] Riepilogo del wizard da loggato senza 500.
- [x] Le 4 guardie sopra esistono sul disco (nomi controllati il 2026-11-12).
- [ ] Remoto a monte corretto e `php -l` prima del mirror (fuori dal tema).
