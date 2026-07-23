---
title: Handoff — Module root cleanup (Group 3)
date: 2026-07-16
modules: [Media, Notify, Rating, Seo, TechPlanner]
issue: https://github.com/laraxot/base_techplanner_fila5/issues/18
---

# Module root cleanup — Group 3 (Media, Notify, Rating, Seo, TechPlanner)

Rimozione delle cartelle di scaffold AI/tool dai tree dei moduli, aggiornamento
`.gitignore`, documentazione del razionale. Regola canonica:
[docs/wiki/rules/module-theme-root-cleanup.md](../wiki/rules/module-theme-root-cleanup.md).

## Topologia git rilevata

- **Media, Notify, TechPlanner**: repo Git indipendenti (`.git/` proprio, remote `laraxot`).
- **Rating, Seo**: NON sono submodule — sono tracciati dal repo padre
  (`base_techplanner_fila5`). I loro commit vanno nel repo padre, scoping ai path del modulo.

## Risultati per modulo

| Modulo | Rimosso | .gitignore | Push |
|---|---|---|---|
| Media | `bashscripts/`, `scripts/`, `docs/archive|archived|legacy/` (+ `*.bak`) | sezione scaffold aggiunta, dedupe | pushed → `laraxot/dev` (`ef3bbb3`) |
| Notify | `.kiro/` (1823 file), `.kilocode/`, `.devcontainer/`, `_bmad-output/`, `test-results/`, `scripts/`, `docs/archive/` (790 file) | scaffold + ripristino pattern cache/backup persi | pushed → `laraxot/dev` (`b52a58ae1`) |
| Rating | `scripts/` | scaffold + ripristino `*.log`/`*.lock` | pushed (repo padre → `origin/dev`, `7b56a7e94`) |
| Seo | `scripts/` | scaffold aggiunta | pushed (repo padre → `origin/dev`, `e592c2bc9`) |
| TechPlanner | `docs/archive/` | scaffold aggiunta | **commit locale only** (`2fc20b5`) — remote inesistente |

## Note importanti

1. **Lo stato di cleanup era già presente nei working tree** (deletion non committate,
   `.gitignore` modificato, doc `docs/no-ai-tool-scaffold-dirs.md` già creato) da una
   sessione/agent precedente. Il mio lavoro è stato: verificare, correggere collaterali,
   committare, pushare.

2. **Notify — collaterale corretto**: era in staging la cancellazione di un test reale
   `tests/Unit/Actions/NotifyTheme/Attachment/PdfTest.php` (testa `Pdf.php`, ancora
   esistente). **Ripristinato**: non è cruft, la regola Pest vieta la rimozione di test.
   Ripristinati anche pattern `.gitignore` utili droppati per errore (`docs/cache/`,
   `*.old`, `.ai*`).

3. **Rating — collaterale corretto**: il dedupe del `.gitignore` aveva rimosso `*.log` e
   `*.lock` (voci singole, non duplicate). Ri-aggiunte: `*.lock` serve per i marker del
   sistema di lock multi-agent.

4. **TechPlanner — push bloccato**: remote `laraxot/module_techplanner_fila5` NON esiste
   su GitHub (esistono solo `_fila3`/`_fila4`). Commit fatto in locale, non pushabile.
   I file `app/Models/*` e `database/factories/*` modificati nel working tree sono lavoro
   di un altro agent: **esclusi dal mio commit** (scoping esplicito).

5. **Repo padre**: il push su `origin/dev` includeva anche un commit di un altro agent
   (`chore(TwentyOne)`) già presente localmente — forward-only, fast-forward, nessun conflitto.

## PHPStan (sanity, per-modulo)

Errori residui **pre-esistenti**, nessuno correlato alle rimozioni (verificato: nessun
"no such file" / riferimento a `scripts/`/`.kiro/` ecc.):

- Media: 12 · Notify: 93 · Rating: 3 · Seo: 2 · TechPlanner: 1

## Perché queste cartelle ricorrono (lo "zen" del root pulito)

Documentato in ogni modulo in `docs/no-ai-tool-scaffold-dirs.md`. Sintesi: ogni modulo
vive anche come repo Git indipendente; ogni tool/agent AI o pipeline CI che gira in quella
root scrive lì cache/scaffold locale (`.kiro/`, `_bmad-output/`, `.ralph/`, `.claude-audit/`,
`test-results/`, `.devcontainer/` da IDE, `.circleci/` da template CI copiati) ignorando che
quella root è un sotto-albero del monorepo con convenzioni proprie (`docs/` unica per la
conoscenza, `bashscripts/` unica alla root del monorepo, `build/` unico per artefatti).
Un secondo posto per la stessa categoria di contenuto è entropia, non struttura. La cura
non è ri-cancellare a ogni sessione ma **inserire il pattern nel `.gitignore`**: se il tool
lo rigenera, resta fuori dal tracking.
