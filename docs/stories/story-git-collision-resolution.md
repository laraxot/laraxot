---
title: "BMAD Story — Git collision recovery e PHPStan gate"
type: story
status: blocked
story_id: git-collision-recovery
module: root
epic: quality
created: 2026-09-25
updated: 2026-09-25
qmd: "git collision merge markers PHPStan Xot audit-coverage forward-only"
related:
  - "../wiki/merge-conflict-task-list.md"
  - "../wiki/merge-conflict-marker-task-list.md"
  - "../wiki/how-to/git-merge-marker-sweep.md"
  - "../chat/2026-09-25-composer-phpstan-blockers.md"
  - "../chat/2026-09-25-phpstan-campaign-coordination.md"
  - "../wiki/rules/merge-conflict-marker-resolution.md"
  - "../wiki/rules/git-forward-only.md"
---

# BMAD Story — Git collision recovery e PHPStan gate

## Understand

Il checkout contiene collisioni Git committate e collisioni ancora presenti nel
working tree. La snapshot grezza rileva **1.444 file di codice** e **22.222
righe marker**; il totale include `audit-coverage/` generato e documentazione
storica, quindi non è un conteggio di file da risolvere meccanicamente. Xot,
Sixteen e Zero sono i primi bootstrap blocker; `laravel/phpstan.neon` è inoltre
modificato e non valido, ma resta esclusivamente sotto controllo umano.

Audit Git aggiuntivo: nessun repository del inventario ha `MERGE_HEAD` o
percorsi `diff-filter=U`; i marker sono quindi corruzione committata o WIP, non
un merge attualmente aperto. I file Xot bootstrap sono già in scrittura da un
altro processo Codex (mtime 2026-09-25 10:18): non forzare lock o patch fino a
quiet window e owner confirmation.

Regole applicabili:

- forward-only: studiare con `git show/log/blame`, patchare illo stato corrente;
- mai `restore`, `reset`, `revert`, `checkout`, `merge` automatico o resolver
  con scelta globale `ours/theirs`;
- lock per-file e status del repository proprietario (`git -C`);
- BMAD/second brain prima del codice, write-back dopo ogni wave;
- niente `audit-coverage/` come sorgente di verità e niente `tests/AuditCoverage/`;
- PHPStan solo con `laravel/phpstan.neon`, mai baseline/ignore/altri `.neon`.

## Plan

1. **Freeze e inventario**: produrre manifest per file con marker, lock, stato
   index/worktree, remote e ultima revisione senza marker.
2. **Xot bootstrap**: confrontare `HEAD`, `laraxot/dev`, commit storico pulito
   e working tree; risolvere prima i file che impediscono autoload/Composer
   (`XotBaseModel`, provider, `XotBasePage`, `Dashboard`, `MainDashboard`,
   `GetModulesNavigationItems`), semanticamente e per lock.
3. **Code non-Xot**: risolvere per module e file, a partire da PHP/Blade/config
   che falliscono `php -l`; non toccare esempi Markdown dentro fence.
4. **Config e manifest**: validare JSON/TOML/YAML/Neon senza sostituire
   `laravel/phpstan.neon`; escalare all’owner umano per il config root.
5. **Generated/legacy**: separare `audit-coverage`, `tests/AuditCoverage`,
   archive/raw e documentazione storica; non usarli come base per il codice.
6. **Gate**: `php -l`, JSON/manifest parse, PHPStan canonico, PHPMD,
   PHPInsights, Pest/owner tests; distinguere errori DB/dependency/WIP.
7. **Chiusura**: sweep finale fuori fence, `git diff --check`, PHPStan completo
   quando il config root è valido, handoff e ticketing.

## AuditCoverage decision

La decisione utente del 2026-09-25 superseda i bridge storici
`audit-coverage/tests/`: la root `audit-coverage/` è un artefatto generato,
va ignorata e non deve esistere. Il SSoT è
[audit-coverage-root-artifact-forbidden](../wiki/concepts/audit-coverage-root-artifact-forbidden.md).
La quiet window ha verificato 48/48 pattern e zero directory fisiche; le
cancellazioni tracciate Xot/User sono intenzionali ma non ancora committate.

## Implement

- [x] Inventario iniziale read-only e classificazione marker.
- [x] Identificato il blocker `phpstan.neon`/autoload e il marker sweep Xot.
- [x] AuditCoverage root cleanup: `audit-coverage/` presente in 48/48 `.gitignore`, directory exact rimosse dopo quiet window; 452 cancellazioni tracciate Xot/User non staged.
- [ ] Xot semantic recovery sui file bootstrap, con lock e history.
- [ ] Non-Xot code collision recovery, diviso per repository.
- [ ] Validazione config/manifest e parsing.
- [ ] PHPStan completo e test di regressione.

## Verify

```bash
# solo dopo lock e owner check
php -l <file.php>
cd laravel && ./vendor/bin/phpstan analyse <scope>
./tools/phpmd.sh <file.php> text phpmd.xml
./tools/phpinsights.sh analyse <scope> --no-interaction
./vendor/bin/pest <owner-tests>
```

 Sweep finale:

```bash
# ancorare il controllo ai marker, non ai separatori Markdown sette '='
rg -n '^(<<<<<<<|>>>>>>>)' laravel/Modules laravel/Themes \
  --glob '!vendor/**' --glob '!node_modules/**'
```

## Document

- [ ] Aggiornare `docs/wiki/merge-conflict-task-list.md` per file effettivamente
  risolto, con owner e data.
- [ ] Aggiornare wiki/log e handoff `docs/chat/`.
- [ ] Aggiornare `docs/chat/INDEX.md` solo dopo quiet window e lock.
- [ ] Aggiornare `docs/wiki/log.md` con esiti e blocker.

## Status

- Branch: `dev`
- Scope: root + repository owner dei file collisionati
- Formale BMAD: **NO-GO** finché `.bmad-core/core-config.yaml` manca.
- Prossimo passo: audit Xot read-only e attesa quiet window dei Codex concorrenti.
- Agent: OpenCode Space Bunny (`opencode-space-bunny`)
- Issue: `DA CREARE` sul repository owner dopo la classificazione.

## GitHub

- Issue: `DA CREARE` — non esiste un issue verificato per questa campagna.
- Discussion: `DA CREARE`.
- Parent/decisions: [Composer/PHPStan blocker](../chat/2026-09-25-composer-phpstan-blockers.md), [PHPStan coordination](../chat/2026-09-25-phpstan-campaign-coordination.md).
