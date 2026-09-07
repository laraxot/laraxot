---
description:
globs:
alwaysApply: false
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./2fa-guide.md"
  - "./2fa.md"
  - "./accessor-delegation-pattern.md"
  - "./actions-path-convention-1.md"
  - "./actions-path-convention-2.md"
  - "./actions-path-convention.md"
<<<<<<< HEAD
=======
>>>>>>> 60a2c9a9 (.)
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
---
# Best Practices Migrazioni XotBaseMigration

## Uso corretto di hasColumn
- Usare SEMPRE `$this->hasColumn('nome_colonna')` nelle migrazioni che estendono XotBaseMigration.
- NON usare mai `Schema::hasColumn`.

### Esempio
```php
if (! $this->hasColumn('state')) {
    $this->tableUpdate(function (Blueprint $table) {
        $table->string('state')->nullable();
    });
}
```

### Motivazione
- Garantisce compatibilità multi-db e coerenza tra i moduli.
- Evita errori dovuti a differenze tra i driver o override custom.

### Checklist
- [ ] Usare solo $this->hasColumn
- [ ] Aggiornare tutte le vecchie migrazioni

## Tipizzazione: sempre TeamContract, mai Team concreto

> **Regola:** Tutti i metodi, relazioni, type hint e return type devono usare `TeamContract` e mai la classe concreta `Team`.

### Motivazione
- Decoupling e sostituibilità
- Testabilità (mocking)
- Compatibilità multi-modulo
- Manutenibilità e refactor sicuro

### Esempio CORRETTO
```php
public function addMember(TeamContract $team, UserContract $user)
```

### Esempio ERRATO
```php
public function addMember(Team $team, User $user)
```

### Checklist
- [ ] Nessun type hint su Team concreto
- [ ] Tutti i type hint su TeamContract

**Vedi anche:**
- [README User](mdc:readme.md)
- [TeamContract](mdc:../app/Contracts/TeamContract.php)

## Collegamenti correlati
- [README User](mdc:readme.md)
- [Linee guida Actions](mdc:actions.mdc)
- [Linee guida Activitylog](mdc:activitylog.mdc)
- [Testing](mdc:testing.md)
<<<<<<< HEAD
<<<<<<< HEAD
- [Documentazione centrale](mdc:../../../../../docs/index.md)
=======
- [Documentazione centrale](mdc:../../../../docs/index.md)
>>>>>>> 60a2c9a9 (.)
=======
- [Documentazione centrale](mdc:../../../../docs/index.md)
=======
- [Documentazione centrale](mdc:../../../../../docs/index.md)
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

## Migration modulari: path corretto obbligatorio

> **Regola:** Tutte le migration di tabelle modulari (es. `teams`) devono essere create solo in `Modules/<Modulo>/database/migrations`.

### Motivazione
- Isolamento e coerenza tra moduli
- Evita conflitti e doppioni
- Manutenzione facilitata

### Errori comuni
- Migration di update/creazione in `database/migrations` globale (da evitare!)

### Checklist
- [ ] Nessuna migration di tabelle modulari nella cartella globale
- [ ] Tutte le migration modulari sono nella cartella del modulo

**Vedi anche:**
- [README User](mdc:readme.md)

## Regola fondamentale sulle migration

- Tutte le migration devono essere nella cartella `database/migrations` del modulo di riferimento.
- Mai mettere migration custom in `laravel/database/migrations`.
<<<<<<< HEAD
<<<<<<< HEAD
- Vedi motivazione e checklist in [PATH_CONVENTIONS.md](./path-conventions-2.md).
=======
- Vedi motivazione e checklist in [PATH_CONVENTIONS.md](./path_conventions.md).
>>>>>>> 60a2c9a9 (.)
=======
- Vedi motivazione e checklist in [PATH_CONVENTIONS.md](./path_conventions.md).
=======
- Vedi motivazione e checklist in [PATH_CONVENTIONS.md](./path-conventions-2.md).
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
