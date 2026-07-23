# segnalazione runtime asset integrity

## Scope tema

Il layout `Sixteen` deve caricare solo asset realmente pubblicati nel webroot attivo.
Path hardcoded non versionati o non deployati introducono 404 e side effect
su Livewire/Alpine.

## Regola operativa

- evitare `<link rel="stylesheet" href="/themes/Sixteen/css/...">`
  se i file non sono parte del deploy
- centralizzare gli stili in `@vite(['resources/css/app.css'], 'themes/Sixteen')`
- garantire presenza asset modulo Geo richiesti dal runtime

## Allineamento modulo

- [segnalazione-runtime-asset-integrity](../../../../Modules/Fixcity/docs/wiki/concepts/segnalazione-runtime-asset-integrity.md)
