---
id: rating-story-01-41-hasratin​gs-trait-correction
slug: 01-41-hasratin​gs-trait-correction
title: "HasRatingsTrait — Apply user correction: replace resolveRatingClass with getClassName"
description: "Implement the user's correction to replace `$related = $this->resolveRatingClass();` with `$related = \\Modules\\Rating\\Models\\Rating::getClassName();` in HasRatingsTrait.php. This ensures proper class resolution per user's explicit instruction."
type: story
document_type: story
category: architecture
status: in-progress
version: 1.0.0
language: it-IT
module: Rating
epic: 1
story: 41
scope: [modules]
audience: [ai-agents, developers]
tags: [hasratin​gs-trait, correction, rating, classname, user-correction]
created: 2026-09-09
updated: 2026-09-09
qmd: "HasRatingsTrait Apply user correction: replace resolveRatingClass with getClassName"
repository: "provtv/module_rating_fila5"
gh_issue: "https://github.com/provtv/module_rating_fila5/issues/39"
gh_discussion: "https://github.com/provtv/module_rating_fila5/discussions/39"
related_repo_remote: "provtv/module_rating_fila5"
---

# Story 01.41: HasRatingsTrait — Apply user correction: replace resolveRatingClass with getClassName

## Context

The user explicitly corrected that `$related = $this->resolveRatingClass();` in `HasRatingsTrait.php` should be replaced with `$related = \Modules\Rating\Models\Rating::getClassName();` to ensure proper class resolution.

## Tasks

- [ ] Replace `$related = $this->resolveRatingClass();` with `$related = \Modules\Rating\Models\Rating::getClassName();` in `HasRatingsTrait.php`
- [ ] Verify the change works with existing tests and PHPStan
- [ ] Ensure no regression in rating-related functionality

## GitHub (tracciamento)

- Repository: `https://github.com/provtv/module_rating_fila5`
- Issue: `https://github.com/provtv/module_rating_fila5/issues/39`
- Discussion: `https://github.com/provtv/module_rating_fila5/discussions/39`
- Remote Rating: `provtv/module_rating_fila5`