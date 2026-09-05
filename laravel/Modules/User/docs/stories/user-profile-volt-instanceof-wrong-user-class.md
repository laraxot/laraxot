---
id: story-user-profile-volt-instanceof-wrong-user-class
slug: story-user-profile-volt-instanceof-wrong-user-class
title: "STORY — ProfileEditVoltComponent controllava instanceof contro la classe User sbagliata"
description: "config/auth.php configura Modules\\Quaeris\\Models\\User come auth model reale, non Modules\\User\\Models\\User (commentato sopra). Le due classi sono sorelle, entrambe figlie di Modules\\User\\Models\\BaseUser, non genitore/figlio. ProfileEditVoltComponent.php controllava instanceof Modules\\User\\Models\\User in tutti e 4 i metodi: sempre falso a runtime per l'utente autenticato reale."
document_type: story
category: bmad
scope: module:User
status: review
version: 1.1.0
language: it-IT
ecosystem: Laraxot
priority: high
created_at: '2026-09-03'
updated_at: '2026-09-04'
tags: [bmad, story, user, phpstan, auth, volt, livewire, production-bug]
related:
  - ../../laravel/Modules/User/app/View/Pages/ProfileEditVoltComponent.php
  - ../../laravel/Modules/User/app/Models/BaseUser.php
  - ../../laravel/Modules/User/app/Models/User.php
  - ../../laravel/Modules/Quaeris/app/Models/User.php
github:
  repository: https://github.com/laraxot/module_user_fila5
  issues: https://github.com/laraxot/module_user_fila5/issues
---

# STORY — ProfileEditVoltComponent controllava instanceof contro la classe User sbagliata

## Contesto

Emersa due volte, in modo indipendente, in due sessioni concorrenti lo stesso
giorno (2026-09-03/04): un `phpstan analyse Modules` di routine segnalava 4
errori `cast.string` / `argument.type` / `method.nonObject` su
`ProfileEditVoltComponent.php` (mount/updateProfile/updatePassword/deleteAccount).

## Problema

`config/auth.php`:

```php
//'model' => env('AUTH_MODEL', Modules\User\Models\User::class),
'model' => Modules\Quaeris\Models\User::class,
```

Il model di auth reale e' `Modules\Quaeris\Models\User`, non
`Modules\User\Models\User`. Le due classi sono **sorelle**, entrambe
`extends Modules\User\Models\BaseUser` — non genitore/figlio.

`ProfileEditVoltComponent.php` faceva `Assert::isInstanceOf($user,
\Modules\User\Models\User::class, ...)` in tutti e 4 i metodi — **sempre
falso** per l'utente autenticato reale. In 3 metodi su 4 l'errore era
mascherato da un `/** @var User $user */` inline (pattern che le istruzioni
stesse di PHPStan vietano esplicitamente: "Do not use assert() or inline @var
PHPDoc tag to override PHPStan's inferred type") — l'assert falliva comunque
a runtime, l'override serviva solo a zittire l'analizzatore. Il quarto metodo
(`deleteAccount`) non aveva l'override ed e' quello che ha fatto emergere i
4 errori PHPStan che hanno acceso l'indagine.

**Effetto a runtime**: ogni tentativo reale di aggiornare il profilo,
cambiare password o cancellare l'account finiva nel blocco `catch`
generico — verosimilmente non funzionante per nessun utente autenticato in
produzione.

## Come e' stata risolta (due volte, riconciliata)

Una sessione ha corretto il file nella sua working tree e committato
(`module_user_fila5@d4647f7e`, versione piu' snella del file, senza i blocchi
`Log::error` estesi). Una seconda sessione (questa), partita da una working
tree che aveva gia' una riscrittura del file piu' ricca (con logging
strutturato) non derivata da quel commit, ha trovato lo stesso bug
indipendentemente (misurando `config/auth.php` da zero) e applicato lo stesso
principio di fix alla propria versione del file, preservando il logging gia'
presente:

- Narrowing contro `BaseUser` (l'antenato comune reale), non `User`, in tutti
  e 4 i metodi — con `if (! $user instanceof BaseUser) { throw new
  RuntimeException(...); }`: `instanceof` nativo narrowa per PHPStan senza
  bisogno di `@var`, a differenza di `Assert::isInstanceOf()` (nessun bridge
  `phpstan-webmozart-assert` installato in questo progetto).
- Rimossi tutti gli override `@var User $user` (compresi 2 aggiunti per
  errore da questa sessione stessa nel primo giro, prima di scoprire il fix
  gia' committato altrove — vedi Dev Agent Record).
- Query di unicita' email: da `User::where(...)` (classe concreta
  hardcoded) a `$user::where(...)` (late static binding sull'istanza gia'
  narrowata).
- `BaseUser.php`: aggiunta `@property string $id` (mancante, presente solo
  sulla sottoclasse `User`); 5 `@property \DateTime|null` corrette in
  `Carbon|null` (Eloquent castta davvero a Carbon, il docblock mentiva).
- `$user->password` e' `string|null` su `BaseUser` (giustamente, non tutti
  gli utenti hanno una password hash — es. social login): aggiunto un vero
  controllo `null === $currentHash` prima di `Hash::check()` invece di un
  altro `@var`/assert non narrowante.

## Acceptance Criteria

- AC1: nessun controllo `instanceof`/`Assert::isInstanceOf` contro
  `Modules\User\Models\User` in `ProfileEditVoltComponent.php` — solo contro
  `BaseUser` (l'antenato comune reale).
- AC2: nessun `@var User $user` (o annotazione equivalente) usato per
  aggirare il narrowing di PHPStan su questo file.
- AC3: `phpstan analyse Modules/User` pulito.
- AC4: `BaseUser.php` dichiara `@property string $id` e usa `Carbon|null`
  (non `\DateTime|null`) per i campi data.
- AC5 (non verificato in questa sessione — DB di test con nota separata,
  vedi [[env-sqlite-manca-suite-non-eseguibile]]): un test Pest end-to-end
  che autentica un utente reale (`Modules\Quaeris\Models\User`) e chiama
  `updateProfile()`/`updatePassword()`/`deleteAccount()` deve passare senza
  finire nel catch generico.

## Tasks/Subtasks

- [x] Task 1: narrowing `BaseUser` nei 4 metodi, rimosso `@var User`
- [x] Task 2: `$user::where()` al posto di `User::where()` hardcoded
- [x] Task 3: `BaseUser.php` — `@property string $id` + 5x `Carbon|null`
- [x] Task 4: null-check reale su `$user->password` prima di `Hash::check()`
- [x] Task 5: PHPStan pulito su `Modules/User` (verificato, 0 errori)
- [ ] Task 6: AC5, test Pest end-to-end — bloccato, vedi Dev Notes

## Dev Notes

- **Suite Pest non affidabile su questo modulo in questa sessione**: con
  `.env.testing` presente e DB `_test` raggiungibile, 417/813 test passano
  ma 396 falliscono con `A facade root has not been set.` /
  `Target class [config] does not exist.` **anche su file mai toccati e in
  isolamento** (verificato: `SendNotificationActionTest.php` nel modulo
  Notify, zero relazione con questo fix, stesso identico fallimento). Non
  diagnosticato in questa sessione — vedi memoria second-brain
  `env-sqlite-manca-suite-non-eseguibile.md` (aggiornata 2026-09-04). AC5
  resta aperto per questo motivo, non per un difetto del fix.
- **Collisione multi-sessione reale su questo file**: vedi commit
  `module_user_fila5@d4647f7e` (altra sessione, versione piu' snella del
  file) e questo stesso fix (versione con logging esteso). Le due versioni
  del file **non sono state riconciliate a livello di riga**: restano due
  varianti dello stesso principio di fix, applicate a due basi di codice
  locali diverse. Chi tocca ancora questo file deve rileggerlo prima di
  editare, non assumere quale delle due versioni sia "quella buona" —
  entrambe lo sono, per il proprio insieme di feature circostanti.
- Root cause reale del bug, non solo sintomo PHPStan: `config/auth.php`
  verificato riga per riga, non per sentito dire.

### References

- [Source: laravel/config/auth.php#L71-L72] — model di auth reale
- [Source: laravel/Modules/Quaeris/app/Models/User.php] — `class User
  extends BaseUser` (sorella, non figlia, di `Modules\User\Models\User`)
- [Source: module_user_fila5@d4647f7e] — fix indipendente della stessa
  sessione gemella, stessa diagnosi, versione del file piu' snella

## Dev Agent Record

### Agent Model Used

Claude Sonnet 5

### Completion Notes List

- **Errore proprio, corretto nella stessa sessione**: il primo giro di
  lavoro (partito solo da `phpstan analyse Modules`, senza controllare
  `git log`/`docs/chat` prima di editare) ha aggiunto 2 `@var User $user`
  per zittire i 4 errori PHPStan — esattamente l'anti-pattern che questa
  story esiste per rimuovere. Scoperto rileggendo `docs/implementation-
  artifacts/sprint-status.yaml` (entry gia' presente da un'altra sessione)
  e verificando `git diff HEAD` sul repo del modulo User, che ha git proprio
  (non submodule): la working tree era gia' indietro rispetto a un fix
  reale committato altrove. Rimossi i 2 `@var` propri e applicato il fix
  corretto (BaseUser, non un cast cosmetico) alla versione locale del file.
- Verificato indipendentemente `config/auth.php` da zero (non fidandosi
  della sola diagnosi altrui) prima di procedere — confermato: stessa
  conclusione.

### File List

- `laravel/Modules/User/app/View/Pages/ProfileEditVoltComponent.php` (fix)
- `laravel/Modules/User/app/Models/BaseUser.php` (fix docblock: `$id` +
  `Carbon|null`)
