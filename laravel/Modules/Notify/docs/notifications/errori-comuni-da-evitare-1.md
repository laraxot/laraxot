---
<<<<<<< HEAD
<<<<<<< HEAD
title: "Errori Comuni da Evitare nelle Implementazioni di Moduli Quaeris"
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
title: "Errori Comuni da Evitare nelle Implementazioni di Moduli Quaeris"
>>>>>>> a988596b (first)
title: "Errori Comuni da Evitare nelle Implementazioni di Moduli App"
type: concept
tags: [errori, comuni, evitare]
created: 2026-07-14
updated: 2026-07-14
<<<<<<< HEAD
<<<<<<< HEAD
qmd: "errori-comuni-da-evitare-1 errori comuni da evitare nelle implementazioni di moduli quaeris"
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
qmd: "errori-comuni-da-evitare-1 errori comuni da evitare nelle implementazioni di moduli quaeris"
>>>>>>> a988596b (first)
qmd: "errori-comuni-da-evitare-1 errori comuni da evitare nelle implementazioni di moduli this-project"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./errori-comuni-da-evitare.md"
  - "./index.md"
  - "./multi-channel-notifications-1.md"
  - "./multi-channel-notifications-2.md"
  - "./multi-channel-notifications.md"
  - "./netfun-sms-implementation-1.md"
  - "./netfun-sms-implementation.md"
  - "./notifications-implementation-guide-1.md"
---

<<<<<<< HEAD
<<<<<<< HEAD
# Errori Comuni da Evitare nelle Implementazioni di Moduli Quaeris
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
# Errori Comuni da Evitare nelle Implementazioni di Moduli Quaeris
>>>>>>> a988596b (first)
# Errori Comuni da Evitare nelle Implementazioni di Moduli App

## Errori di Struttura Directory e Namespace

1. **Errore di Case nelle Directory**
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
- ❌ ERRATO: `/var/www/html/Quaeris/laravel/Modules/Notify/App/Actions/`
   - ✅ CORRETTO: `/var/www/html/Quaeris/laravel/Modules/Notify/app/Actions/`
- ❌ ERRATO: `/var/www/html/Quaeris/laravel/Modules/Notify/App/Actions/`
   - ✅ CORRETTO: `/var/www/html/Quaeris/laravel/Modules/Notify/app/Actions/`
<<<<<<< HEAD
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
- ❌ ERRATO: `/var/www/_bases/base_ptvx_fila5/laravel/Modules/Notify/App/Actions/`
   - ✅ CORRETTO: `/var/www/_bases/base_ptvx_fila5/laravel/Modules/Notify/app/Actions/`
   
   > Le directory standard di Laravel sono sempre in lowercase (`app`, `config`, `resources`, ecc.)

2. **Errore di Namespace nei File**
   - ❌ ERRATO: `namespace Modules\Notify\App\Actions;`
   - ✅ CORRETTO: `namespace Modules\Notify\Actions;`
   
   > Il namespace dipende dalla configurazione PSR-4 nel composer.json del modulo

## Errori di Configurazione

1. **Duplicazione di Configurazioni Generiche**
   - ❌ ERRATO: Aggiungere retry, rate limit, timeout nella sezione specifica del provider
   - ✅ CORRETTO: Usare le sezioni generiche esistenti per questi comportamenti comuni

   ```php
   // ERRATO
   'drivers' => [
       'provider' => [
           'api_key' => env('PROVIDER_KEY'),
           'retry_attempts' => 3,  // ERRORE: Duplicazione
       ],
   ],
   
   // CORRETTO
   'drivers' => [
       'provider' => [
           'api_key' => env('PROVIDER_KEY'),
       ],
   ],
   'retry' => [
       'attempts' => env('SMS_RETRY_ATTEMPTS', 3),
   ],
   ```

2. **Modifica di Moduli Riutilizzabili**
   - ❌ ERRATO: Modificare file di configurazione in moduli riutilizzabili
   - ✅ CORRETTO: Estendere la configurazione in file separati o fare richieste ai mantenitori

## Errori di Implementazione

1. **Mancata Separazione tra Configurazione e Logica**
   - ❌ ERRATO: Codificare valori di configurazione direttamente nell'implementazione
   - ✅ CORRETTO: Usare le configurazioni esistenti nelle implementazioni

   ```php
   // ERRATO
   public function execute() {
       $timeout = 30; // Hardcoded
   }
   
   // CORRETTO
   public function execute() {
       $timeout = config('sms.timeout');
   }
   ```

2. **Utilizzo di Client HTTP Diversi**
   - ❌ ERRATO: Usare `Illuminate\Support\Facades\Http` quando il modulo usa `GuzzleHttp\Client`
   - ✅ CORRETTO: Seguire le convenzioni esistenti nel modulo per coerenza

## Best Practices per Evitare Errori

1. **Analisi Prima dell'Implementazione**
   - Esamina sempre la struttura esistente del modulo
   - Verifica il composer.json per il mapping PSR-4
   - Controlla le implementazioni esistenti per convenzioni di naming e pattern

2. **Separazione delle Responsabilità**
   - Configurazione: definizione di parametri
   - Implementazione: logica di business e utilizzo
   - Documentazione: spiegazione e guida d'uso

3. **Principio DRY (Don't Repeat Yourself)**
   - Non duplicare configurazioni generiche
   - Riutilizzare componenti e logiche esistenti
   - Centralizzare comportamenti comuni