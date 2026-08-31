# ASD Soci Ramdivision mockup funzionale

## Obiettivo

Mockup Filament dedicato a una **web app per la gestione soci di una ASD**, costruito con lo stesso approccio dei pacchetti `WTS`, `DDS` e `GiveBack`, ma orientato a:

- iscrizione e rinnovo quote soci;
- anagrafiche complete con codice fiscale, contatti e dati sportivi essenziali;
- aggiornamento del libro soci;
- gestione entrate e uscite ricorrenti dell'associazione;
- adesione a eventi di calendario;
- integrazione base con fatturazione e sicurezza 2FA;
- accesso da browser desktop e mobile con impostazione app-like.

## In poche parole
Il mockup deve far capire questo messaggio:

> Un'unica web app per gestire iscrizioni, rinnovi, libro soci, eventi e movimenti economici di base della ASD, con accesso semplice per segreteria e soci.

Il focus del progetto non e creare una contabilita completa, ma dare alla ASD uno strumento operativo ordinato e riutilizzabile ogni stagione.

## Pannello

- Panel id: `asd-soci`
- Path: `/asd-soci`
- Theme asset: `resources/css/filament/asd-soci/theme.css`
- Provider: `app/Providers/Filament/AsdSociPanelProvider.php`

## Ruoli dedicati

- `asd_admin`
  - accesso completo al pannello `asd-soci`;
  - gestisce soci, eventi, movimenti, export e configurazioni.
- `asd_secretary`
  - accesso a iscrizioni, rinnovi, libro soci, documenti ed eventi;
  - non vede configurazioni tecniche avanzate.
- `asd_treasurer`
  - accesso a quote, ricevute, entrate, uscite, sponsorizzazioni e versamenti liberali;
  - puo consultare esportazioni amministrative.
- `asd_member`
  - accesso alla propria area personale;
  - puo iscriversi, rinnovare, aggiornare dati ammessi, aderire agli eventi e consultare ricevute.
- `asd_event_manager`
  - accesso a calendario eventi, adesioni e check-in;
  - non gestisce contabilita generale o libro soci completo.

## Comando utenti

```bash
php artisan auth:create-asd-soci-user admin@ramdivision.local "Ramdivision Admin" --password=secret --role=asd_admin
php artisan auth:create-asd-soci-user segreteria@ramdivision.local "Segreteria Ramdivision" --password=secret --role=asd_secretary
php artisan auth:create-asd-soci-user tesoreria@ramdivision.local "Tesoreria Ramdivision" --password=secret --role=asd_treasurer
php artisan auth:create-asd-soci-user socio@ramdivision.local "Socio Demo" --password=secret --role=asd_member
```

## UI map

- `UI-01 - Landing iscrizione e rinnovo`
  - accesso rapido da telefono;
  - registrazione nuovo socio;
  - rinnovo quota annuale;
  - stato pratica e ricevuta.
- `UI-02 - Onboarding socio e verifica dati`
  - anagrafica completa;
  - codice fiscale;
  - contatti;
  - eventuale patente o licenza sportiva;
  - privacy e consensi.
- `UI-03 - Backoffice domande di ammissione`
  - coda pratiche da approvare;
  - delibera consiglio;
  - attivazione nel libro soci;
  - produzione ricevuta e numero progressivo.
- `UI-04 - Libro soci e storico`
  - elenco soci attivi, cessati e sospesi;
  - vista coerente con il tracciato Excel condiviso;
  - export massivo per trasmissioni sportive o adempimenti.
- `UI-05 - Quote, ricevute ed entrate`
  - quote associative;
  - tesseramenti;
  - sponsorizzazioni;
  - versamenti liberali;
  - vendita merchandising.
- `UI-06 - Uscite e spese associazione`
  - banca;
  - telefono;
  - imposte o tasse;
  - assicurazioni;
  - affitti;
  - segreteria;
  - spese social;
  - acquisti merchandising;
  - spese varie.
- `UI-07 - Calendario eventi e adesioni`
  - eventi pubblici o riservati ai soci;
  - adesione da area personale;
  - lista iscritti e stato pagamento.
- `UI-08 - Area socio`
  - profilo;
  - rinnovo quota;
  - ricevute;
  - documenti;
  - storico partecipazioni agli eventi.
- `UI-09 - Export e trasmissioni`
  - esportazione libro soci;
  - export quote e movimenti;
  - pacchetti massivi per invii amministrativi.
- `UI-10 - Sicurezza e accessi`
  - login con 2FA;
  - recupero account;
  - audit base accessi.
- `UI-11 - Fatturazione e Fatture in Cloud`
  - invio dati economici verso il sistema di fatturazione;
  - tracciamento stato documento;
  - gestione manuale o semi-automatica da confermare.
- `UI-12 - Configurazione associazione`
  - stagioni associative;
  - quote standard;
  - causali;
  - tipi evento;
  - template ricevute;
  - parametri export.

## Entita principali

- `MemberApplication`
- `Member`
- `MembershipSeason`
- `MembershipRenewal`
- `MembershipReceipt`
- `MemberRegisterEntry`
- `MemberDocument`
- `Event`
- `EventRegistration`
- `AccountingEntry`
- `AccountingCategory`
- `DonationOrSponsorship`
- `MerchandiseSale`

## MVP consigliato

Copertura mockata:

- landing iscrizione e rinnovo;
- onboarding socio;
- backoffice approvazione;
- libro soci;
- quote e ricevute;
- calendario eventi;
- area socio;
- export base;
- sicurezza 2FA.

Copertura funzionante consigliata nella prima fase:

- registrazione nuovo socio;
- rinnovo quota annuale;
- delibera e attivazione socio nel libro soci;
- stampa o download ricevuta;
- esportazione libro soci in Excel/CSV;
- gestione adesioni eventi;
- registrazione entrate e uscite essenziali.

## Fuori MVP o da validare

- contabilita completa con prima nota avanzata;
- riconciliazione bancaria automatica;
- integrazione completa Store Apple/Google con app nativa;
- tessera digitale con wallet mobile;
- integrazione real-time con sistemi federali esterni;
- firma elettronica avanzata con valore legale;
- merchandising con ecommerce completo;
- multi-associazione o multi-sede avanzato;
- workflow complessi di approvazione oltre al consiglio direttivo base.

## Nota importante
Il mockup serve per vedere e discutere i passaggi. Non e ancora il prodotto finito.

Le schermate devono far capire a colpo d'occhio:

- cosa fa il socio;
- cosa fa la segreteria;
- cosa fa la tesoreria;
- quali dati entrano nel libro soci;
- quali parti restano da validare prima dello sviluppo finale.
