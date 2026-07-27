# Bugfix — XotBaseMigration model resolution failure (media)

## Problem

`php artisan migrate` failed with:

```
Expected an existing class name. Got: "\Modules\Document\Models\Medium"
```

`XotBaseMigration::getModelClass()` derives the model name from the migration filename
`create_media_table` via `Str::singular('media')`, which returns the grammatically
correct English singular `medium`. The actual model is `Media` (irregular — same
convention as Spatie's MediaLibrary), so the guesser's output never matches.

## Fix

Set `protected ?string $model_class = Media::class;` explicitly on the migration,
bypassing the naming-convention guesser.

## How to avoid next time

Any table name that is already a Latin plural treated as an English mass noun
(`media`, `data`, `criteria`, ...) will trip `Str::singular()` into producing the
"correct" but unused grammatical singular. Set `$model_class` explicitly on those
migrations rather than relying on convention.
