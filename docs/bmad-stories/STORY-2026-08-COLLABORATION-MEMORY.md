---
id: STORY-2026-08-COLLABORATION-MEMORY
title: COLLABORAZIONE + MEMORIA — Ricorda tutto, installa se serve, non ripetere
status: active
priority: critical
---

# Regole di collaborazione (non ripetere mai queste cose)

- Sempre `git remote -v` per verificare `laraxot/module_xot_fila5`
- Sempre `git fetch laraxot dev` prima di iniziare
- Sempre `git log --oneline -15 laraxot/dev` per vedere cosa fanno altri agenti
- Se un altro agente sta lavorando sugli stessi file: coordinare via BMAD story
- Sempre `bashscripts/lock/check.sh` prima di editare un file
- Sempre `php -l` su ogni file modificato
- Sempre `pint --dirty --test` prima del commit
- Sempre `phpstan analyse <modulo> --no-progress` dopo il fix
- Sempre `pest --filter=<modulo> --coverage` se possibile
- Sempre `git status` pulito prima del push
- Sempre `docs/` aggiornato (modulo + tema)
- Sempre BMAD entry creata
- Sempre second brain aggiornato (`docs/bmad/`, `docs/wiki/`)

# Non ripetere queste azioni
- Non `RefreshDatabase` → `.env.testing` SQLite
- Non `RefreshDatabase` → `.env.testing` SQLite
- Non `RefreshDatabase` → `.env.testing` SQLite
- Non `git push --force` o `--force-with-lease`
- Non `git reset --hard`, `rebase`, `stash drop`, `clean -fd`
- Non `git checkout` per tornare indietro (solo `git log`, `git show`, `git blame`)
- Non `phpstan -c /tmp/foo.neon` o `--level` alternativo
- Non `@phpstan-ignore` o `mixed` senza commento
- Non `phpmd` o `phpinsights` saltati
- Non `git commit` senza `story: <id>`
- Non `git commit` senza `docs/` aggiornato

# Cosa installare / usare se serve
Se il codice non funziona o manca qualcosa:
```bash
# Se manca `bmad create story`
# Cerca `bmad-method` in `docs/` o `docs/wiki/bmad-method.md`

# Se manca PHPStan
./vendor/bin/phpstan analyse Modules/ --no-progress --memory-limit=-1

# Se manca Pint
./vendor/bin/pint --dirty --test

# Se manca Pest
./vendor/bin/pest --filter=<modulo> --coverage

# Se manca PHPMD
./tools/phpmd.sh <modulo>

# Se manca PHPInsights
./tools/phpinsights.sh analyse <modulo> --min-quality=80 --min-complexity=80 --min-style=80
```

# Ricerca internet (se serve capire)
Se non capisci un errore (`type_coverage`, `#[\Override]`, `HasXotTable`, `XotBaseResourceTable`):
- Cerca `bmad` su `docs/`
- Cerca `larastan/phpstan` su `vendor/`
- Cerca `Filament` su `docs/`
- Cerca `Modules/Xot/app/Filament/`
- Leggi `README.md` del modulo
- Leggi `AGENTS.md` (`# AGENTS.md — On-Demand Stub`)

# Per non dimenticare (se perdi tutto)
Leggi queste storie in ordine:
1. `STORY-2026-08-RESTART.md`
2. `STORY-2026-08-COMPLETED.md`
3. `STORY-2026-08-UNFINISHED.md`
4. `STORY-2026-08-DISCOVERED.md`
5. `STORY-2026-08-SUGGESTED.md`
6. `STORY-2026-08-STRUCTURAL-XOT-FORM-INFO.md`
7. `STORY-2026-08-HASXOTFORM-INSTANCE.md`

# Collaborazione con altri agenti
Quando collabori:
- Scrivi sempre `git remote -v`
- Scrivi sempre il nome dell'altro agente se c'è (`git log --author`)
- Usa BMAD story come unico punto di verità
- Non lavorare mai su file che un altro agente sta modificando (verifica con `git log --all -- <file>`)
- Se devi lavorare sullo stesso file: crea una BMAD discussion e indica chi fa cosa (`STORY-...`)
- Sempre `docs/` aggiornato (modulo + tema + bmad-stories)
- Sempre `docs/bmad-stories/STORY-...md` con stato aggiornato (`proposed` → `in-progress` → `completed`)
