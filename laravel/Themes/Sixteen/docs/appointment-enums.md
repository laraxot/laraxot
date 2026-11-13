---
title: "Appointment enums (Sixteen)"
type: reference
tags: [sixteen, appointment, enum, filament, i18n]
created: 2026-10-06
updated: 2026-10-08
status: draft
qmd: "appointment enums (sixteen) sixteen"
---

# Appointment enums (Sixteen)

Le costanti `Appointment::STATUS_*` e `Appointment::SERVICE_*` sono state sostituite da due backed enum
nel tema. Story: [stories/const-to-enum-sixteen-appointment.story.md](stories/const-to-enum-sixteen-appointment.story.md).

## Dove vivono (PSR-4 reale)

`Themes/Sixteen/composer.json` mappa **solo** `Themes\Sixteen\` -> `app/`. Gli enum stanno quindi in
`Themes/Sixteen/app/Enums/` (namespace `Themes\Sixteen\Enums`). `src/` NON e' autoloaded (vedi "Duplicazione").

| Enum | Valori | Uso |
|---|---|---|
| `AppointmentStatusEnum` (`HasLabel`, `HasColor`, `HasIcon`) | `PENDING=pending`, `CONFIRMED=confirmed`, `COMPLETED=completed`, `CANCELLED=cancelled`, `NO_SHOW=no_show` | cast di `sixteen_appointments.status`; `isOpen()` = pending o confermato (cancellabile/modificabile entro le finestre 24h/48h del modello) |
| `AppointmentServiceTypeEnum` (`HasLabel`) | `ANAGRAFE`, `TRIBUTI`, `SUAP`, `URP`, `OTHER` | tipi di servizio prenotabile (nessuna colonna ancora li persiste) |

## Traduzioni

Label, colore e icona stanno in `lang/<locale>/appointment_status_enum.php` e
`lang/<locale>/appointment_service_type_enum.php` (chiavi `values.<valore>.label|color|icon`), locale `it` ed `en`
(de/es del tema sono parziali: fallback `en`). Namespace `sixteen::`, registrato da `ThemeServiceProvider`
quando il tema e' attivo.

`Modules\Xot\Traits\EnumTrait` NON e' usato: `TransTrait::getKeyTransClass()` assume `Modules\<M>\<Type>\<Classe>`
e per `Themes\Sixteen\Enums\...` produrrebbe una chiave rotta. Gli enum risolvono le chiavi da soli.

## Migrazione dal vecchio codice

| Prima | Dopo |
|---|---|
| `Appointment::STATUS_PENDING` | `AppointmentStatusEnum::PENDING` (in query: `->value`) |
| `in_array($status, [PENDING, CONFIRMED])` | `$this->status?->isOpen()` |
| `$status === self::STATUS_CANCELLED` | `$status === AppointmentStatusEnum::CANCELLED` |
| `Appointment::getStatuses()` / `getServiceTypes()` | invariati nella firma (`[valore => label]`), ora tradotti dagli enum |

Il modello dichiara `casts()` con `'status' => AppointmentStatusEnum::class`; `@property AppointmentStatusEnum|null $status`
(null solo per modelli non ancora valorizzati).

## Duplicazione `src/` vs `app/` (NON risolta)

`src/Models/Appointment.php` e `src/Http/Livewire/Appointment/CreateAppointment.php` sono copie legacy non
autoloaded delle versioni in `app/`; `src/Models/Appointment.php` aveva costanti duplicate (fatal error "Cannot
redefine class constant" se caricato). Sono stati allineati agli enum ma non cancellati ne' ripuliti: decidere se
eliminare `src/` (il resto di `src/` contiene anche conflitti di merge non risolti, es. `src/Models/Municipal/MunicipalEvent.php`).
`phpstan analyse Themes/Sixteen` si interrompe finche' quei file hanno errori di parse.

## Test

`Themes/Sixteen/tests/Unit/AppointmentEnumsTest.php` (Pest, sqlite in-memory): valori, `isOpen()`, risoluzione
delle chiavi lang it/en, cast del modello.

## Aggiornamento 2026-10-08

Il merge da `laraxot/dev` aveva riportato `Models/Appointment.php` e `CreateAppointment` alle `const` (enum, lang e test erano
rimasti): conversione ri-applicata. `getLabel()` e `options()` degli enum vengono ora dal trait `Enums/Concerns/HasLangLabel`,
condiviso con i 21 enum dei modelli `Municipal`. Il `fallback_locale` dell'app e' `it` (de/es tornano in italiano). `src/` non
esiste piu' nel tema: la sezione "Duplicazione" sopra e' storica. Story:
[stories/services-to-actions-sixteen.story.md](stories/services-to-actions-sixteen.story.md).
