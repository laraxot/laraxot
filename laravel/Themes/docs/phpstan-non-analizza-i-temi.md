---
title: PHPStan non analizza i temi
type: reference
updated: 2026-08-31
related:
  - ./ide-helper-models-and-theme-models.md
---

# PHPStan non analizza i temi

## Il fatto

`laravel/phpstan.neon` dichiara un solo path:

```neon
paths:
    - ./Modules/
```

`./Themes/` non c'e'. Il comando che il progetto considera la verifica di qualita'
canonica, `./vendor/bin/phpstan analyse`, riporta `[OK] No errors` **senza aver mai
letto una riga dei temi**.

## Perche' conta

Analisi manuale del 2026-08-31, lanciata passando i path a mano:

```bash
cd laravel && ./vendor/bin/phpstan analyse --memory-limit=-1 Themes/
```

Risultato: **1387 errori** a livello max, di cui 1319 in `Sixteen` e 68 in
`TwentyOne`. Tra questi, difetti che sono crash a runtime e non debito di
tipizzazione:

| Difetto | Dove | Effetto |
|---|---|---|
| `$this__('chiave')` | `TwentyOne/.../ResolveFeaturedMarketsGridDataAction` | identificatore corrotto, 11 occorrenze, fatal |
| `Themes\Sixteen\Models\User` | `SpidAuthController`, `CieAuthController` | classe inesistente: login SPID e CIE non funzionanti |
| `$this->emit()` | `Livewire/Appointment/CreateAppointment` | API Livewire 2 su progetto Livewire 3 |
| `foreach` su `DOMNodeList\|false` | `SpidAuthAction` | fatal sul parsing della risposta SAML |
| closure `Attribute` tipizzata `void` che ritorna | `Municipal/PublicPerson`, `PublicDocument` | fatal all'accesso all'accessor |

Tutti corretti il 2026-08-31. Nessuno sarebbe emerso dal comando canonico.

## Cosa fare

Aggiungere `./Themes/` ai `paths` di `phpstan.neon` e assorbire il debito residuo,
oppure — se il volume va gestito per gradi — introdurre una configurazione dedicata ai
temi con un baseline, in modo che almeno le regressioni nuove vengano intercettate.

Finche' non e' fatto, ogni intervento su un tema va verificato a mano:

```bash
cd laravel && ./vendor/bin/phpstan analyse --memory-limit=-1 Themes/<Nome>
```

La modifica a `phpstan.neon` spetta al responsabile del progetto: il file e' fuori
dalla portata degli agenti.
