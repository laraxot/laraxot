---
title: "[DEV] Costanti -> enum: Appointment (tema Sixteen)"
type: dev
status: done
created: 2026-10-06
story: const-to-enum-sixteen-appointment.story.md
---

# [DEV] Costanti -> enum: Appointment (tema Sixteen)

## Technical Plan

1. Leggere `composer.json` del tema: PSR-4 = `app/` -> enum in `app/Enums/`.
2. Due backed enum string (stato con `HasLabel/HasColor/HasIcon`, tipo servizio con `HasLabel`), traduzioni in
   `lang/{it,en}/*_enum.php` con chiavi `values.<valore>.label|color|icon`, namespace `sixteen::`.
3. `Appointment`: rimuovere le costanti, `casts()` con l'enum, `->value` nella query, `isOpen()` al posto di `in_array`.
4. `getStatuses()/getServiceTypes()` mantenuti (API pubblica) ma costruiti dagli enum.
5. `CreateAppointment`: `AppointmentStatusEnum::PENDING`.
6. Stessa sostituzione nei file duplicati di `src/`, senza cancellarli.

## Files to Modify

- new `Themes/Sixteen/app/Enums/AppointmentStatusEnum.php`, `AppointmentServiceTypeEnum.php`
- new `Themes/Sixteen/lang/{it,en}/appointment_status_enum.php`, `appointment_service_type_enum.php`
- mod `Themes/Sixteen/app/Models/Appointment.php`, `src/Models/Appointment.php`
- mod `Themes/Sixteen/app/Http/Livewire/Appointment/CreateAppointment.php`, `src/Http/Livewire/Appointment/CreateAppointment.php`
- new `Themes/Sixteen/tests/Unit/AppointmentEnumsTest.php`
- docs: `docs/appointment-enums.md`, `docs/index.md`, `docs/00-INDEX.md`, questa coppia story/dev

## Implementation Steps

- [x] Verificare PSR-4 e chiamanti (`grep` su Themes e Modules, Blade incluse)
- [x] Creare enum e lang it/en
- [x] Aggiornare `app/Models/Appointment.php` (casts(), isOpen(), query `->value`)
- [x] Aggiornare `CreateAppointment` (app + src)
- [x] Allineare `src/Models/Appointment.php` (rimossi i duplicati di costante rotti)
- [x] Test Pest dedicato
- [x] Doc + indici + story

## Testing

`APP_ENV=testing ./vendor/bin/pest Themes/Sixteen/tests/Unit/AppointmentEnumsTest.php` (phpunit.xml: sqlite `:memory:`) -> 3 test, 51 assert, verdi.
Il test registra a mano il namespace `sixteen::` (il ThemeServiceProvider lo registra solo con tema attivo).

## Verification

```bash
cd laravel
php -l Themes/Sixteen/app/Models/Appointment.php   # + file toccati
./vendor/bin/phpstan analyse Themes/Sixteen/app/Enums Themes/Sixteen/app/Models/Appointment.php \
  Themes/Sixteen/app/Http/Livewire/Appointment/CreateAppointment.php --memory-limit=-1 --no-progress
./vendor/bin/pint --test Themes/Sixteen/app/Enums Themes/Sixteen/tests/Unit/AppointmentEnumsTest.php
```

PHPStan (file toccati): `app/Models/Appointment.php` 0 -> 0; `app/.../CreateAppointment.php` 1 -> 1 (view-string preesistente, non toccato);
enum 0. `src/Models/Appointment.php` 14 -> 44 e `src/.../CreateAppointment.php` 83 -> 83: l'aumento in `src/Models` e' debito latente
della copia legacy (non tipizzata) prima nascosto dal fatal "class.duplicateConstant"; non fixato (copia non autoloaded, vedi decisione).
`phpstan analyse Themes/Sixteen` intero si interrompe: parse error in `src/Models/Municipal/*` e errore interno larastan su
`app/Models/Municipal/OrganizationalUnit.php` (file di altri), quindi nessun conteggio complessivo affidabile.

## Lessons Learned

- Prima di toccare un tema verificare il PSR-4 reale: `src/` non e' mappato, quindi e' codice morto/duplicato, ma PHPStan lo analizza.
- `EnumTrait` presuppone `Modules\<M>\...`: per i temi serve risolvere le chiavi lang nell'enum (`sixteen::...`).
- Un file con costanti duplicate nasconde gli altri errori PHPStan: sistemarlo "aumenta" il conteggio ma e' debito reale, non regressione.
- Il namespace `sixteen::` non esiste nei test se il tema non e' attivo: registrarlo nel `beforeEach`.
- Mantenere i metodi pubblici legacy (`getStatuses()`) come wrapper sottili evita di rompere chiamanti non visibili, e porta le label in lang.

## Decisioni da chiedere all'utente

- Eliminare (o sincronizzare) la copia `Themes/Sixteen/src/`? Contiene conflitti di merge irrisolti e non e' autoloaded.
