---
title: "[STORY] Costanti -> enum: Appointment (tema Sixteen)"
type: story
status: done
priority: medium
created: 2026-10-06
---

# [STORY] Costanti -> enum: Appointment (tema Sixteen)

## User Request

«al posto di ste cagate public const STATUS_PENDING = 'pending'... passa ad usare enum, cerca i const in tutto il
progetto e sostituiscili con qualcosa di meglio». Contesto swarm: «sistema tutte le segnalazioni di phpstan [...]
concentrati sullo scopo/funzionalita, non sull'errore; aumenta la qualita del codice; usa enum al posto delle costanti».

## Analysis

- `Appointment` (`sixteen_appointments`) modella le prenotazioni di servizi comunali (AGID). `status` guida cancellabilita
  (24h), modificabilita (48h), promemoria (confermato + domani) e timestamp di cancellazione.
- PSR-4 reale: `Themes\Sixteen\` -> `app/` (composer.json del tema). `src/` non e' autoloaded: copia legacy; la sua
  `Appointment` aveva costanti duplicate (STATUS_CONFIRMED/COMPLETED/CANCELLED, SERVICE_TRIBUTI/SUAP/URP) = fatal error.
- Due domini distinti: stato (5 valori) e tipo servizio (5 valori). Nessun enum preesistente per lo stesso dominio
  (`grep "case PENDING"` -> solo domini diversi).
- Consumatori: solo `CreateAppointment` (app e src) usa `STATUS_PENDING`; `getStatuses()/getServiceTypes()` e le
  `SERVICE_*` non hanno chiamanti; nessuna Blade/Modules li usa.
- `EnumTrait` non e' applicabile ai temi (chiave lang derivata da `Modules\...`).

## Acceptance Criteria

- [x] Enum `AppointmentStatusEnum` e `AppointmentServiceTypeEnum` in `app/Enums/` (PSR-4 reale)
- [x] Label/colore/icona da lang del tema (it, en), nessuna stringa hardcoded
- [x] `status` castato all'enum; query con `->value`; confronti con case dell'enum; logica `isOpen()` nell'enum
- [x] Aggiornati `app/` E `src/` (Model + CreateAppointment); nessun file cancellato/spostato
- [x] Nessun consumatore residuo di `Appointment::STATUS_*`/`SERVICE_*` in Themes/Modules
- [x] `php -l` OK; PHPStan sui file toccati senza nuovi errori in `app/`; test Pest verdi
- [x] Doc `appointment-enums.md` + indice aggiornati

## GitHub (tracciamento)

Issue: TODO (gh non installato su questa macchina)
Discussion: TODO
