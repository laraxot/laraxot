---
title: "Comment — CommentConfigData (un file, CloudFrontData pattern)"
type: concept
module: Comment
tags: [comment, config, spatie-data, ssot, kiss]
created: 2026-06-10
updated: 2026-06-10
qmd: "CommentConfigData make one file flat properties config comments"
issues:
  - "https://github.com/laraxot/module_comment_fila5/issues/13"
  - "https://github.com/laraxot/base_fixcity_fila5/issues/322"
discussions:
  - "https://github.com/laraxot/module_comment_fila5/discussions/14"
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/323"
related:
  - ./no-comment-config-ssot.md
  - ../../../../docs/stories/STORY-292-remove-comment-config.md
---

# CommentConfigData — KISS

Un solo file `app/Datas/CommentConfigData.php`, come `CloudFrontData`.

```php
$config = CommentConfigData::make();

if ($config->allow_anonymous_comments) { /* ... */ }

$model = $config->models['comment'];
$mentionsOn = $config->mentions['enabled'] ?? false;
```

- Scalari: proprietà dirette (`allow_anonymous_comments`, …)
- Sezioni config: `array` (`models`, `mentions`, `ui`, …)
- Solo helper per `app()`: notifiche, sanitizer, transformers

Vietato: `config('comments.*')` nel dominio, cartelle `Datas/Config/*`.

## GitHub

| Repo | Issue | Discussion |
|---|---|---|
| module_comment_fila5 | [#13](https://github.com/laraxot/module_comment_fila5/issues/13) | [#14](https://github.com/laraxot/module_comment_fila5/discussions/14) |
| base_fixcity_fila5 | [#322](https://github.com/laraxot/base_fixcity_fila5/issues/322) | [#323](https://github.com/laraxot/base_fixcity_fila5/discussions/323) |
