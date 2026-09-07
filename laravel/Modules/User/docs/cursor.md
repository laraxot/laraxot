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
# Regola Cursor: Migrazioni XotBaseMigration

## Regola
- Usare SEMPRE `$this->hasColumn('nome_colonna')` nelle migrazioni che estendono XotBaseMigration.
- NON usare mai `Schema::hasColumn`.

## Esempio
```php
if (! $this->hasColumn('state')) {
    $this->tableUpdate(function (Blueprint $table) {
        $table->string('state')->nullable();
    });
}
```

## Motivazione
- Compatibilità multi-db
- Coerenza tra i moduli
- Evita errori dovuti a override custom

## Checklist
- [ ] Usare solo $this->hasColumn
- [ ] Aggiornare tutte le vecchie migrazioni

## Collegamenti correlati
- [README User](mdc:readme.md)
- [Best Practices](mdc:best-practices.mdc)
- [Linee guida Actions](mdc:actions.mdc)
- [Linee guida Activitylog](mdc:activitylog.mdc)
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

