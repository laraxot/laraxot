---
title: "Standard <nome progetto>: spatie/laravel-queueable-action"
module: "Job"
type: concept
tags: [queueable, action]
created: 2026-07-14
updated: 2026-07-14
qmd: "queueable action"
related:
  - "./phpstan-fixes-archive-2.md"
---
# Standard <nome progetto>: spatie/laravel-queueable-action

In tutto il progetto <nome progetto>, **NON si utilizzano Service class custom**. La business logic asincrona e le azioni riutilizzabili sono gestite SEMPRE tramite [spatie/laravel-queueable-action](https://github.com/spatie/laravel-queueable-action).

## Vantaggi rispetto ai Service

- Azioni invocabili sia sincrone che asincrone
- Testabilità e riuso
- Dispatch asincrono semplice
- Chiarezza architetturale

## Pattern di utilizzo

```php
use Spatie\QueueableAction\QueueableAction;

class SendWelcomeEmailAction
{
    use QueueableAction;
    public function execute(User $user): void
    {
        // logica
    }
}
// Sincrono
(new SendWelcomeEmailAction())->execute($user);
// Asincrono
(new SendWelcomeEmailAction())->dispatch($user);
```

## Collegamenti

- [Documentazione ufficiale](https://github.com/spatie/laravel-queueable-action)
- [README Notify](../laravel/modules/notify/docs/readme.md)
