---
title: Stato di qualita' dei temi
type: reference
updated: 2026-08-31
related:
  - ./phpstan-non-analizza-i-temi.md
  - ./ide-helper-models-and-theme-models.md
  - ../Modules/Xot/docs/stato-qualita-progetto-2026-08-31.md
---

# Stato di qualita' dei temi — misurazione 2026-08-31

I temi non rientrano nel gate PHPStan del progetto (vedi
[phpstan-non-analizza-i-temi](phpstan-non-analizza-i-temi.md)). Questa pagina misura
cosa c'e' sotto, per non lavorare a sensazione.

Comando di riferimento:

```bash
cd laravel && ./vendor/bin/phpstan analyse --memory-limit=-1 Themes/
```

---

## Numeri

Al 2026-08-31, dopo le correzioni della stessa data: **1330 errori** a livello max.

Non sono tutti debito reale. La ripartizione onesta:

| Categoria | Conteggio | Natura |
|---|---|---|
| `larastan.noEnvCallsOutsideOfConfig` | 184 | **falso positivo**, vedi sotto |
| `missingType.iterableValue` | 196 | debito di tipizzazione |
| `method.nonObject` | 159 | tipizzazione, ma alcuni sono difetti veri |
| `missingType.generics` | 138 | tipizzazione |
| `argument.type` | 115 | tipizzazione, alcuni difetti veri |
| `property.nonObject` | 107 | tipizzazione, alcuni difetti veri |
| `missingType.return` | 86 | tipizzazione |
| `missingType.parameter` | 66 | tipizzazione |
| `property.notFound` | 58 | PHPDoc dei model incompleti |
| altro | 221 | misto |

**Debito reale: circa 1146.**

### Il falso positivo da 184

Tutte e 184 le segnalazioni `env()` cadono in file che sono configurazione a pieno
titolo:

```
96  Sixteen/config/sixteen.php
49  Sixteen/config/spid.php
38  Sixteen/config/cie.php
 1  Sixteen/config/theme.php
```

La regola larastan riconosce come lecito solo il `config/` alla radice
dell'applicazione, e non sa che i temi hanno un proprio `config/`. Il codice e'
corretto: usa `env()` esattamente dove deve. Quando i temi entreranno nel gate, questa
regola va esclusa per `Themes/*/config/*`, non "sistemata" nel codice. Riscrivere quei
file per far tacere PHPStan sarebbe un peggioramento.

### I file piu' densi

| Errori | File |
|---|---|
| 137 | `Sixteen/app/Models/Municipal/MunicipalNews.php` |
| 107 | `Sixteen/app/Models/Municipal/PublicDocument.php` |
| 104 | `Sixteen/app/Models/Municipal/MunicipalLocation.php` |
| 103 | `Sixteen/app/Models/Municipal/MunicipalEvent.php` |
|  81 | `Sixteen/app/Http/Livewire/Appointment/CreateAppointment.php` |
|  79 | `Sixteen/app/Models/Municipal/MunicipalService.php` |
|  66 | `Sixteen/app/Models/Municipal/PublicPerson.php` |
|  57 | `Sixteen/app/Models/Municipal/OrganizationalUnit.php` |
|  50 | `Sixteen/app/Actions/MenuBuilderAction.php` |

Sette file su nove sono i model di `Municipal/`. Non e' una coincidenza ed e' una buona
notizia: il debito e' concentrato, non sparso.

---

## Difetti corretti il 2026-08-31

Non erano tipizzazione: erano crash.

| Difetto | File | Effetto |
|---|---|---|
| `$this__('chiave')`, 11 occorrenze | `TwentyOne/app/Actions/Markets/ResolveFeaturedMarketsGridDataAction.php` | identificatore corrotto da una sostituzione automatica; fatal a ogni chiamata |
| `Themes\Sixteen\Models\User`, classe inesistente | `SpidAuthController`, `CieAuthController` | login SPID e CIE non funzionanti; sostituita con `XotData::make()->getUserClass()` e tipo `UserContract` |
| `$this->emit()` | `Http/Livewire/Appointment/CreateAppointment.php` | API Livewire 2 su progetto Livewire 3; sostituita con `dispatch()` |
| `foreach` su `DOMNodeList\|false` e `getAttribute()` su `DOMNode` | `app/Actions/SpidAuthAction.php` | fatal nel parsing della risposta SAML; aggiunti i guardi di tipo |
| closure `Attribute` tipizzata `void` che restituisce un valore | `Municipal/PublicPerson.php`, `Municipal/PublicDocument.php` | fatal all'accesso all'accessor |

I due ultimi sono stati trovati per una via indiretta: verificando quale ramo vincesse
tra `app/` e `src/`, il caricamento delle classi andava in errore. Vedi
[ide-helper-models-and-theme-models](ide-helper-models-and-theme-models.md).

---

## Cosa fare, in ordine

1. **Escludere `Themes/*/config/*` dalla regola `env()`** prima di qualunque altra
   cosa, altrimenti 184 falsi positivi nascondono il debito vero.
2. **Bonificare i model `Sixteen/app/Models/Municipal/`**: sette file valgono circa
   650 errori. Sono anche i file che usano `protected $casts` invece del metodo
   `casts()`, e che hanno scope senza tipi (`scopeActive($query)`). Un solo passaggio
   sistematico sui sette chiude meta' del debito.
3. **Decidere dove vivono questi model.** Cittadini, servizi e documenti comunali sono
   dominio applicativo: appartengono a un modulo, non a un tema. Finche' restano qui,
   ogni regola pensata per i moduli li manca.
4. **Inserire `./Themes/` nel gate**, con baseline se serve, per fermare le
   regressioni nuove.

---

## Cosa non e' stato misurato

- La resa visiva e l'accessibilita' dei temi nel browser.
- I test dei temi: non eseguiti, il database non ha tabelle e i dati sono sacri.
- Gli asset frontend e la configurazione Vite.
