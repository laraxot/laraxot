---
qmd: "up"
issues: []
discussions: []
title: 'Up'
module: Rating
type: reference
slug: up
description: 'aggiungere al file laravel/Modules/IndennitaResponsabilita/app/Models/Rating.php /** * Get the attributes that should be cast. * * @return array<string, string> */ protected function casts(): array { '
tags: [migrato-da-txt, rating]
converted_from: up.txt
created: 2026-08-24
updated: 2026-08-24
status: stale
---

> Nota 2026-10-14 (docs-audit): Chiede di modificare laravel/Modules/IndennitaResponsabilita/app/Models/Rating.php, percorso che non esiste in questo repository (verificato 2026-10-14). Appunto di un altro progetto. Contenuto non riscritto, verificare prima di usarlo.

aggiungere   al file laravel/Modules/IndennitaResponsabilita/app/Models/Rating.php
 /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'extra_attributes' => SchemalessAttributes::class,
            'rule' => RuleEnum::class,
            'is_disabled' => 'boolean',
            'is_readonly' => 'boolean',
        ];
    }

    /**
     * Scope to query by extra attributes.
     *
     * @param Builder $query
     * @param array<string, mixed>|string $attributes
     * @param mixed $value
     * @return Builder
     */
    public function scopeWithExtraAttributes(Builder $query, array|string $attributes = [], mixed $value = null): Builder
    {
        if (is_string($attributes) && $value !== null) {
            // Single attribute with value: withExtraAttributes('anno', 2024)
            return $query->where("extra_attributes->{$attributes}", $value);
        }

        if (is_array($attributes)) {
            // Multiple attributes: withExtraAttributes(['anno' => 2024, 'type' => 'foo'])
            foreach ($attributes as $key => $val) {
                $query = $query->where("extra_attributes->{$key}", $val);
            }
        }

        return $query;
    }

    // -------------------------------------------------


e' sbagliato ! perche' sono rindondati e dovrebbero stare dentro laravel/Modules/Rating/app/Models/BaseRating.php
