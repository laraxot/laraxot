---
title: "Sixteen - PHP non valido dopo il mirror: recupero"
type: story
bmad: true
status: done
created: 2026-11-10
owner: Themes/Sixteen (file toccati anche in Themes/TwentyOne e laravel/compare-html.php)
related:
  - ../../../../Modules/Fixcity/docs/stories/production-hardening-p0.story.md
  - ../../../../Modules/Xot/docs/stories/binary-files-gitattributes-png-recovery.story.md
---

# PHP non valido dopo il mirror

- Fase BMAD: Quick Flow (rilevazione, causa, fix, verifica)
- Branch: `dev`

## Problema

`php -l` su tutti i 13.395 file PHP tracciati sotto `laravel/` ha trovato 16 file che non compilano, tutti introdotti dal
commit `9fd38b4912` ("mirror al root di moduli e temi dopo il pull"):

- 15 file di `Themes/Sixteen`: `ThemeServiceProvider`, `SpidAuthAction`, `SpidAuthController`, `CieAuthController`,
  `CreateAppointment`, `Appointment`, i sette modelli `Models/Municipal/*`, `SixteenComposer`, `routes/auth.php`;
- `Themes/TwentyOne/resources/views/components/blocks/stats/explosive_numbers.blade.php`.

Con il `ThemeServiceProvider` non valido il tema non si avvia: nessun deploy del branch è possibile.

## Causa

Fusione testuale sbagliata nel repository a monte `laraxot/theme_sixteen_fila5` (ramo `dev`, commit `53f949d`, anch'esso
con gli stessi 15 file rotti), poi copiata qui dal mirror. Le righe della versione vecchia e di quella nuova sono finite
incollate sulla stessa riga (es. `$this->app['view']->composer([        $this->app->make('view')->composer([`), con
`use` duplicati e blocchi `try` senza `catch`. Il lato "vecchio" rimetteva anche codice superato: funzioni non `Safe\`,
annotazioni PHPStan tolte, `@use HasFactory<...>` rimosso.

La ricerca del segno della fusione (due istruzioni incollate con 6+ spazi) su tutte le righe PHP aggiunte dal mirror
trova danni solo in questi file.

## Correzione

- I 16 file tornano alla versione di `9fd38b4912^`, che compilava ed era la più recente integra. Il gate SPID
  (`digitalIdentityRoutesEnabled`) resta.
- `laravel/compare-html.php` (strumento di sviluppo): `fetchHtml()` era dichiarata `void` ma restituiva l'HTML; ora
  `?string` con `null` in caso di errore.

## Verifica

- `php -l` su tutti i file PHP tracciati: restano solo 5 file che usano `new Classe()->metodo()` senza parentesi esterne
  (sintassi valida da PHP 8.4, la versione richiesta da `composer.json`; l'ambiente di verifica ha PHP 8.3):
  `Fixcity/app/Actions/CreateTicketAction.php`, `User/app/Models/Traits/HasTeams.php` e tre test.
- PHPStan e Pest non eseguiti: niente `vendor/` in questo ambiente.

## Aperto

- **A monte:** `laraxot/theme_sixteen_fila5` ramo `dev` ha ancora i 15 file rotti. Il prossimo mirror li riporta qui.
  La correzione va fatta lì (questa sessione ha solo accesso in lettura). Prima di ogni mirror: `php -l` sui file in arrivo.
- Il mirror `9fd38b4912` cambia 1.342 file. Fuori dai 16 rotti non è stato rivisto riga per riga: i file validi possono
  comunque contenere regressioni dello stesso tipo (codice vecchio che sostituisce quello nuovo).

## Altre regressioni del mirror corrette

Lo stesso mirror aveva annullato due correzioni del 2026-11-05 (story `production-hardening-p0`):

- `Themes/Sixteen/.gitignore`: tornate le righe `components`, `layouts`, `laravel`, `.c*` del ramo sbagliato del vecchio
  conflitto. Facevano ignorare ogni nuovo componente o layout Blade del tema. Tolte di nuovo; `git check-ignore` su un
  componente nuovo: non ignorato.
- `pages/area-personale/pratiche.blade.php` nel tema: tolto di nuovo. È un doppione della pagina del modulo User e chiama
  `route('segnalazioni')`, che non esiste.

Controllati e integri: `BlockShowcasePages`, gate SPID, build Geo in `deploy.sh`, `EditTicket::WORKFLOW_FIELDS`, migrazioni
DB-01/DB-02, `.gitattributes` (nessuna riga `!text` attiva), firme PNG (nessun nuovo PNG corrotto).
