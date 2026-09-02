# Roadmap — Rating, il modulo più piccolo con la coscienza più pulita

> Numeri misurati: [`docs/cosa-migliorare.md`](cosa-migliorare.md) (80,
> 2026-09-01) — PHPStan 0, PHPMD app/ 38 ⚠, Code 97.6, Arch 78.6, 125 casi
> test, coverage 80,8%. Coverage più alta dei cinque moduli qui analizzati e
> il Code score migliore: coerente con quello che segue, un modulo piccolo
> che si prende cura di sé — con l'eccezione dei due trait morti sotto.

59 file PHP, il più snello dei cinque moduli qui analizzati, e l'unico dove i
`@phpstan-ignore` trovati in `app/` non sono scuse ma referti firmati:

```
app/Models/Traits/RatingTrait.php:20
/** @phpstan-ignore trait.unused (verificato zero consumer in questo repo il 2026-09-01) */

app/Models/Traits/HasRating.php:12
/** @phpstan-ignore trait.unused (verificato zero consumer reale il 2026-09-01 —
solo riferimenti a `HasRatingContract`/namespace `Actions\HasRating\*`, non `use HasRating;`) */
```

Questa è la differenza fra sopprimere un errore e *chiudere un'indagine*: chi
ha scritto quel commento ha verificato, con data, che nessuno usa quei trait,
e lo ha scritto per il prossimo che passa di lì — coerente con la regola di
questo progetto per cui "ogni soppressione nasconde un errore vero" finché
non è dimostrato il contrario. Qui il contrario è dimostrato. Il passo
successivo, ovvio e mai fatto: **se sono morti da prima del 2026-09-01,
cancellali**. Un `@phpstan-ignore` con verifica scritta sopra è un cadavere
con l'autopsia già firmata; lasciarlo lì è solo procrastinazione con le
credenziali in regola.

## Tre repository path che non portano da nessuna parte in CI

`composer.json` dichiara `repositories` verso `../Xot`, `../Tenant`, `../UI`
— ma **nessuno di questi tre nomi compare nel blocco `require`**. Sono
dipendenze fantasma: dichiarate per l'IDE o per un'epoca passata del modulo,
mai effettivamente richieste da composer. In un monorepo va bene, il
resolver le ignora; ma è il primo indizio, insieme a `require-dev: []`, che
questo `composer.json` non è mai stato pensato per un `composer install`
standalone. Se Rating dovesse mai avere una pipeline CI reale (il progetto ci
sta lavorando adesso, modulo per modulo), quei path repository vanno
sostituiti con i veri repository VCS GitHub (`laraxot/module_xot_fila5`,
`laraxot/module_tenant_fila5`, `laraxot/module_ui_fila5` — il pattern di
naming è meccanico) oppure rimossi se davvero non servono.

## 172 documenti per 59 file di codice

Un rapporto di quasi 3 documenti per ogni file PHP. `bad-practices.md` e
`BAD_PRACTICES.md` coesistevano (collisione di case, lo stesso pattern già
visto altrove nel monorepo), così come `best-practices.md` /
`BEST_PRACTICES.md`. Su un filesystem case-insensitive questi sarebbero già
un errore fatale; sopravvivevano solo perché Linux è permissivo, non
perché qualcuno li avesse voluti entrambi. `docs/00-INDEX.md` esiste, quindi
il modulo ha già un punto d'ingresso — usarlo per marcare come deprecati (o
cancellare) i cluster duplicati sarebbe più utile di scriverne un altro.

**Aggiornamento 2026-09-02**: `bad-practices.md`/`BAD_PRACTICES.md` erano
byte-identici, cancellata la copia uppercase. `best-practices.md`/
`BEST_PRACTICES.md` NON erano deduplicabili per contenuto: uno rimandava a
un canonical Themes vuoto (`.gitkeep-Modules`), l'altro descriveva classi
(`RatingService`, `RatingStar`, `AverageRating`) inesistenti nel modulo —
verificato con grep, zero risultati. Nessuno dei due era tenibile; sostituiti
con una nota onesta in `best-practices.md`, vedi quel file.

## Priorità concrete

1. ~~Cancellare `RatingTrait`/`HasRating` se l'indagine del 2026-09-01 regge
   ancora~~ — riverificato 2026-09-02: zero consumer, ancora vero. Tenuti con
   motivo scritto (`@phpstan-ignore trait.unused`), non cancellati: sono API
   di piattaforma dichiarate, non morte per errore. Vedi
   `docs/chat/phpstan-ignore-audit-2026-09-02.md`.
2. Popolare `require-dev` e decidere il destino dei tre `repositories` path
   inutilizzati, come primo passo verso una CI reale.
3. ~~Deduplicare `bad-practices.md`/`BAD_PRACTICES.md` e
   `best-practices.md`/`BEST_PRACTICES.md`~~ — fatto 2026-09-02.

Rating è il modulo di questi cinque più vicino a essere davvero pulito: gli
basta smettere di accumulare fantasmi — di trait e di file — invece di
continuare a documentarli.
