## [2026-07-06] phpstan | rimosso test scaffold senza dominio

- Eliminato `tests/Unit/SumTest.php`: test generico `sum()` non legato al modulo Blog.
- Motivazione: PHPStan su `Modules` generava errore severo tentando di leggere un path derivato inesistente `Modules/Blog/app/tests/Unit/SumTest.php`.
- Regola operativa: non creare classi/path fittizi per soddisfare test scaffold; correggere o rimuovere il test che cerca qualcosa fuori dominio.
- Verifica finale: `cd laravel && ./vendor/bin/phpstan analyse Modules` -> `[OK] No errors`.

## [2026-06-05] docs | HackerNoon harness — tips 001-022 in wiki locale

- Stub/checklist: second-brain → canon Xot, ai-harness, [hackernoon map](../../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md), [llm-wiki.txt](../../../../../bashscripts/tools/prompts/llm-wiki.txt)
- GitHub: [#272](https://github.com/laraxot/base_fixcity_fila5/issues/272) / [D#273](https://github.com/laraxot/base_fixcity_fila5/discussions/273)

# Blog Wiki Log

## [2026-04-15] init | wiki bootstrap
- Struttura wiki/log.md inizializzata.
- Layer raw: tutti i file in `docs/` (eccetto `wiki/`).
- Layer wiki: `docs/wiki/` — LLM-maintained, sintesi ad alto riuso.
- Schema: `docs/.schema/WIKI_SCHEMA.md`
- Adozione moduli: `docs/project/llm-wiki-module-adoption.md`
