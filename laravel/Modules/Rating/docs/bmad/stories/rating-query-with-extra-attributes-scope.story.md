# Story: rating-query-with-extra-attributes-scope
**Status**: done
**Modulo**: Rating / IndennitaResponsabilita
**Epic**: usare `spatie/laravel-schemaless-attributes` tramite `scopeWithExtraAttributes`

## AC
- [x] Sostituire `Rating::where('extra_attributes->anno', $anno)->where('extra_attributes->type', $type)` con `Rating::withExtraAttributes(['anno' => $anno, 'type' => $type])`
- [x] Verificare che `BaseRating::scopeWithExtraAttributes()` usa il filtro schemaless
- [x] Aggiornare `IndennitaResponsabilitaResource.php` con la nuova sintassi
- [x] PHPStan (gate 5.181 / scope IR)

## Note
- `spatie/laravel-schemaless-attributes` già in `composer.lock` (^2.5.1)
- Chiusa 2026-09-23: codice IR già su `withExtraAttributes`; story era stale `ready-for-dev`
- Follow-up catalogo riusabile: [18.60](./18.60-rating-xls-fields-catalog.story.md) + arch [rating-xls-fields-reusable](../architecture/rating-xls-fields-reusable.md)
- Second brain: `bashscripts/ai/wiki/memories/schemaless-attributes-query.md`
