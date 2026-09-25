<<<<<<< HEAD
# base_workorder_fila5

Monorepo Laraxot per il field service: commesse, interventi, preventivi, fatturazione, magazzino, produzione, conformità e verticali di settore, costruiti come moduli Laravel indipendenti.

| Cosa cerchi | Dove |
|---|---|
| L'applicazione Laravel, i moduli e la loro tabella misurata | [laravel/README.md](./laravel/README.md) |
| Un singolo modulo | `laravel/Modules/<Nome>/README.md` |
| Second brain, regole, memorie, story BMAD | [docs/wiki/](./docs/wiki/), [docs/stories/](./docs/stories/), [docs/sprint-status.yaml](./docs/sprint-status.yaml) |
| Script operativi e loro documentazione | [bashscripts/docs/](./bashscripts/docs/) |
| Pianificazione BMAD (PRD, architettura, epics) | [bmad-output/](./bmad-output/) |
| Istruzioni per gli agenti | [CLAUDE.md](./CLAUDE.md), [AGENTS.md](./AGENTS.md) |

Comandi principali, dalla cartella `laravel/`:

```bash
composer dev                                          # server, coda, log, Vite
composer test                                         # Pest
./vendor/bin/phpstan analyse Modules --memory-limit=-1
bash ../bashscripts/tools/readme/module-readme-badges.sh --host --check   # README stantii o non agnostici
```
=======
# laraxot
>>>>>>> laraxot/dev
