# PHPStan zero — puntatore (non più inventario)

Questo file **non** è più lo stato vivo. L'inventario «1891 errori / 17 moduli»
del 2026-07-03 è storico: copiarlo come baseline oggi è un falso.

SSoT corrente:

- [phpstan-status.md](./phpstan-status.md) — misura verificata
- [wiki/troubleshooting/phpstan-modules-fix.md](./wiki/troubleshooting/phpstan-modules-fix.md) — come si dichiara uno zero
- [18.59](./stories/18.59.phpstan-repo-wide-zero-2026-09-21.story.md) — ultimo drift chiuso (23→0)

Misura 2026-09-21: `phpstan analyse` (senza path CLI) → **0**, `totals.file_errors: 0`, exit 0.
