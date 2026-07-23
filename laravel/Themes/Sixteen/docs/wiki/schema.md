# Sixteen Wiki Schema

## Scope

This schema governs the compiled wiki under `laravel/Themes/Sixteen/docs/wiki/`.

## Layer Mapping

- Source umbrella: `../`
- Imported external sources: `../sources/`
- Compiled wiki: `./`

## Operations

### Ingest

1. Read the relevant source material from `../`.
2. Distill durable findings into one or more wiki pages.
3. Link back to the source-area documents that justify the synthesis.
4. Update `index.md` and append to `log.md`.

### Query

1. Check `index.md`.
2. Prefer the wiki over raw parity dumps.
3. Use source docs only when the wiki does not yet cover the question.

### Lint

Check:

- missing wiki entries for recurring page families;
- dead links to source docs;
- stale frontend decisions after parity work changed;
- duplicated guidance spread across many reports.

## Good Wiki Pages

- parity strategy summaries;
- accessibility patterns;
- layout decisions;
- asset-pipeline decisions;
- archived answers for recurring frontend questions.
