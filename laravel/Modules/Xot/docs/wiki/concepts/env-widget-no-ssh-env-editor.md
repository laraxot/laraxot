---
title: "EnvWidget — editor .env da pannello admin, nessun SSH/FTP richiesto"
type: concept
status: canonical
module: Xot
created: 2026-09-17
updated: 2026-09-20
tags: [env, widget, filament, config, deploy, no-ssh, artisan-commands-manager, mail, smtp, mail-from]
qmd: "EnvWidget EnvData env editor no ssh no ftp config cache artisan commands manager sms_driver mail_mailer mail_host mail_port smtp mail_from_address mail_from_name MAIL_FROM_ADDRESS MAIL_FROM_NAME"
related:
  - ../../../../Notify/docs/wiki/concepts/sms-channel-driver-selection.md
  - ../../../../Quaeris/docs/stories/quaeris-envwidget-mail-config-fields.md
  - ./artisan-migrate-no-force.md
---

# `EnvWidget` — modificare il `.env` di produzione senza SSH/FTP

## Problema che risolve

Su un server di produzione senza accesso SSH/FTP, non c'è modo di aprire il file
`.env` a mano. `EnvWidget` (`Modules\Xot\Filament\Widgets\EnvWidget`) è un widget
Filament che legge e riscrive `.env` **dal processo PHP della richiesta web
stessa** — chi ha accesso al pannello admin può cambiare variabili d'ambiente
senza toccare il filesystem del server con altri strumenti.

## Come funziona

- [`EnvData`](../../../app/Datas/EnvData.php) (`Spatie\LaravelData\Data`) dichiara un
  set **fisso** di proprietà pubbliche (una per variabile esposta, minuscolo:
  `app_url`, `debugbar_enabled`, `google_maps_api_key`, `telegram_bot_token`,
  `sms_driver`, `netfun_token`, …).
  - `EnvData::make()` legge `$_ENV`, abbassa le chiavi e costruisce l'istanza
    via `self::from($data)` — Spatie Data ignora le chiavi non dichiarate come
    proprietà, quindi **solo le variabili con una proprietà dedicata sono
    leggibili/scrivibili**, non tutto `.env`.
  - `EnvData::update(array $data)` confronta ogni valore passato con quello
    corrente e, se diverso, riscrive la riga `CHIAVE=valore` dentro
    `base_path('.env')` (`File::get()` + `File::put()`), o la aggiunge in coda
    se la chiave non esiste ancora. I valori stringa vengono scritti tra
    doppi apici (`CHIAVE="valore"`) — sintassi valida per dotenv.
- [`EnvWidget`](../../../app/Filament/Widgets/EnvWidget.php) espone un form
  Filament sopra `EnvData`. `getFormSchema()` costruisce **tutti** i campi
  possibili in `$all`, poi filtra con la proprietà pubblica `only` (passata da
  chi istanzia il widget) — così ogni pagina mostra solo il sottoinsieme
  pertinente. `submit()` chiama `EnvData::make()->update($this->data)` e
  mostra una notifica di conferma.
- Ogni modulo con una pagina "Impostazioni" (`XotBasePage`) monta il widget con
  il proprio `only`, es. [`Notify\SettingPage`](../../../../Notify/app/Filament/Pages/SettingPage.php):

  ```php
  public function getHeaderWidgets(): array
  {
      $only = [
          'debugbar_enabled', 'telegram_bot_token',
          'sms_driver', 'netfun_token',
          'mail_mailer', 'mail_host', 'mail_port', 'mail_encryption',
          'mail_username', 'mail_password', 'mail_from_address', 'mail_from_name',
      ];

      return [EnvWidget::make(['only' => $only])];
  }
  ```

## Aggiungere una nuova variabile editabile

Non basta che la chiave esista in `.env`: va dichiarata esplicitamente, in tre punti.

1. **`EnvData`** — nuova proprietà pubblica, minuscolo, stesso nome della
   variabile (`SMS_DRIVER` → `sms_driver`). Valore di default sensato (di
   solito stringa vuota) per il caso in cui la variabile non sia ancora nel
   `.env`.
2. **`EnvWidget::getFormSchema()`** — nuova voce nell'array `$all`, chiave
   uguale alla proprietà. Preferire un `Select` con opzioni chiuse quando il
   valore valido è un enum applicativo (es. `sms_driver`: solo i driver
   effettivamente mappati in `SmsActionFactory`) — un `TextInput` libero
   permette refusi che rompono silenziosamente la funzionalità solo al primo
   invio reale.
3. **La pagina `SettingPage` del modulo interessato** — aggiungere la chiave
   al proprio array `only`. Senza questo passo il campo esiste nel widget ma
   non compare in nessuna pagina.

Esempio reale: `sms_driver` aggiunto il 2026-09-17 per permettere di cambiare
`config('sms.default')` (`SMS_DRIVER`, vedi
[Notify — sms-channel-driver-selection](../../../../Notify/docs/wiki/concepts/sms-channel-driver-selection.md))
dal pannello admin di Notify, perché il `.env` di produzione non è
raggiungibile via SSH/FTP. Punto di partenza: [issue module_quaeris_fila5#38](https://github.com/laraxot/module_quaeris_fila5/issues/38).
Stesso giorno, aggiunto anche `netfun_token` (`TextInput` semplice, non un
`Select`: il valore è una stringa libera fornita dal provider, non un enum
applicativo) — stesso motivo pratico: senza SSH, l'unico modo per **verificare**
cosa c'è oggi in `NETFUN_TOKEN` in produzione (non solo per scriverlo) è
vederlo pre-compilato in questo campo, dato che `mount()` carica sempre il
valore corrente da `$_ENV` prima che l'utente tocchi nulla.

Secondo esempio reale: `mail_mailer`/`mail_host`/`mail_port`/
`mail_encryption`/`mail_username`/`mail_password` aggiunti il 2026-09-20,
stesso motivo pratico ma per `MAIL_*` invece di `SMS_*` — diagnosticare un
invio email che va in timeout dal server nuovo
([module_quaeris_fila5#46](https://github.com/laraxot/module_quaeris_fila5/issues/46))
senza poter aprire il `.env` di produzione via SSH. `mail_mailer` e
`mail_encryption` sono `Select` a opzioni chiuse (stesso motivo di
`sms_driver`: i valori validi sono un enum applicativo, `config('mail.mailers')`
per il primo, `tls`/`ssl`/nessuna per il secondo — non testo libero, per
evitare refusi che rompono l'invio solo al primo tentativo reale). Story:
[quaeris-envwidget-mail-config-fields.md](../../../../Quaeris/docs/stories/quaeris-envwidget-mail-config-fields.md).

Terzo esempio reale: `mail_from_address`/`mail_from_name` (`MAIL_FROM_ADDRESS`/
`MAIL_FROM_NAME`) aggiunti il 2026-09-20 sulla stessa pagina, nel gruppo
**Mail**. Sono due `TextInput` liberi (indirizzo e nome del mittente non sono
un enum applicativo) e, a differenza dei campi SMTP, valgono con **qualunque**
`mail_mailer`, non solo `smtp`. Due dettagli da conoscere:

- `$_ENV` contiene il valore **già risolto**: con `MAIL_FROM_NAME="${APP_NAME}"`
  nel `.env` (default del progetto) il campo si apre pre-compilato col nome
  dell'app, non con la stringa `${APP_NAME}`. Finché il campo non viene
  modificato, `EnvData::update()` non riscrive quella riga (confronta il
  valore del form con quello letto all'apertura), quindi il riferimento
  `${APP_NAME}` sopravvive; se invece lo si modifica, nel `.env` finisce il
  testo letterale scritto. Coperto da test in `EnvWidgetTest`.
- Nessuna validazione `->email()` su `mail_from_address`: `submit()` legge
  `$this->data` senza `form->getState()`, e `->email()` renderebbe l'input
  `type=email`, bloccando dal browser il salvataggio di **tutto** il form se
  il valore corrente non è un indirizzo valido (succede: `.env.development`
  ha `MAIL_FROM_ADDRESS="${APP_NAME}"`).

**Nota sicurezza**: `netfun_token` e `mail_password` (come già
`telegram_bot_token` prima) compaiono in chiaro nel form, senza
mascheramento — coerente con gli altri campi-segreto già esposti da questo
widget, ma da tenere presente: chiunque abbia accesso alla pagina
Impostazioni di Notify vede questi valori in chiaro. Per `mail_password`
questa scelta è stata confermata esplicitamente dall'utente (non un default
assunto), vedi la story collegata.

## Dopo il salvataggio: la config cache

`EnvWidget` scrive il `.env` su disco, ma se in produzione è stato eseguito
`php artisan config:cache`, Laravel legge la configurazione dal file cache
compilato, **non** da `.env` — la modifica resta invisibile finché la cache
non viene rigenerata.

Stesso vincolo di accesso: nessun SSH per lanciare `php artisan config:cache`
a mano. Soluzione già disponibile — [`ArtisanCommandsManager`](../../../app/Filament/Pages/ArtisanCommandsManager.php)
(modulo Xot) ha un bottone **"config:cache"** che esegue il comando reale
tramite [`ExecuteArtisanCommandAction`](../../../app/Actions/ExecuteArtisanCommandAction.php)
(whitelist fissa di comandi consentiti, nessun input arbitrario). Sequenza
completa senza SSH/FTP per cambiare una variabile d'ambiente in produzione:

1. Pagina Impostazioni del modulo (usa `EnvWidget`) → cambia il valore → Salva.
2. `Artisan Commands Manager` (Xot) → bottone **config:cache** (solo se la
   config è davvero cache-ata, vedi sotto) e/o **queue:restart**.

**Attenzione — corretto il 2026-09-20, la versione precedente di questo
paragrafo era sbagliata** ("il bottone è sicuro anche se la config non era
cache-ata"): con la config cache-ata Laravel **non carica più il `.env`**
(`LoadEnvironmentVariables::bootstrap()` esce subito se
`configurationIsCached()`), quindi `EnvData::make()`, che legge `$_ENV`,
apre il form con i valori di default (verificato: `app_url` →
`http://localhost`, `mail_host` → vuoto). Conseguenze pratiche:

- **Se la pagina Impostazioni mostra i valori correnti, la config in
  produzione NON è cache-ata**: non lanciare `config:cache` — non serve
  (le modifiche al `.env` valgono subito per le nuove richieste web) e
  renderebbe il form vuoto.
- I worker `queue:work` invece caricano la config una volta sola all'avvio:
  dopo aver cambiato una variabile serve **queue:restart** perché
  rileggano il `.env` (un supervisor/daemon deve poi riavviarli).
- `config:cache` ha senso solo se la config è già cache-ata: in tal caso il
  form non è affidabile come specchio del `.env`.

## Limiti noti

- **`mail_encryption` non influisce sull'invio** (verificato nel vendor,
  Laravel/Symfony di questo progetto): `MailManager::createSmtpTransport()`
  ignora `MAIL_ENCRYPTION`; il TLS implicito si attiva solo con porta
  esatta `465`, ogni altra porta usa STARTTLS. Il campo è quindi
  informativo. La porta invece conta davvero: il 2026-09-20 un timeout
  SMTP in produzione era la porta `465` non raggiungibile dal server
  nuovo, risolto passando a `587` da questo widget
  ([story](../../../../Quaeris/docs/stories/quaeris-go-live-real-batch-verification-findings.md)).

- Il set di variabili editabili è **statico e cablato in `EnvData`/`EnvWidget`**,
  non uno scanner generico di `.env` — coerente con la scelta di sicurezza
  (nessun editor libero di chiave/valore arbitrario esposto in produzione).
- `EnvData::update()` scrive solo le chiavi il cui valore è cambiato rispetto
  a quello letto da `$_ENV` all'apertura del form — se il form resta aperto a
  lungo mentre qualcun altro modifica `.env` altrove, il salvataggio può
  sovrascrivere quella modifica esterna con il valore "vecchio" per quella
  proprietà (comportamento condiviso da qualunque form basato su uno snapshot
  caricato al `mount()`).
- **Corretto il 2026-09-20 — l'affermazione precedente qui era sbagliata**:
  `Modules/Xot/lang/{it,en,de}/env.php` **è** collegato a questo widget, non
  è un concetto scollegato. `EnvWidget.php` non chiama `__()` esplicitamente,
  ma non serve: `Modules\Lang\Providers\Filament\LangServiceProvider::
  registerFilamentLabel()` registra un `Field::configureUsing()`/
  `Section::configureUsing()` **globale**, applicato automaticamente a ogni
  campo/Section Filament dell'intera applicazione (non solo a `EnvWidget`).
  Quel hook (`Modules\Lang\Actions\Filament\AutoLabelAction`) risolve
  `label`/`placeholder`/`helperText`/`description` da
  `{modulo}::env.fields.<nome_campo>.<chiave>` (per una `Section`,
  `{modulo}::env.sections.<heading>.<label|heading>`) — verificato dal vivo,
  non dedotto: il primo caricamento della pagina Impostazioni **scrive da
  solo** le voci mancanti in `env.php`, con il nome del campo/dell'heading
  come valore segnaposto. Conseguenza pratica: **non usare `->label()`/
  `->placeholder()`/`->helperText()` direttamente su un campo di questo
  widget** (viola la regola generale del progetto, vedi
  `bashscripts/ai/wiki/rules/filament-rules-summary.md`) — impostare invece
  il testo vero direttamente nel file di traduzione, alla chiave che il
  meccanismo genera automaticamente al primo caricamento.
