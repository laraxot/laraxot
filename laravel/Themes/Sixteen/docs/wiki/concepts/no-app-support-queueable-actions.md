---
title: "no app/Support — theme Actions"
type: concept
tags: [sixteen, theme, actions, queueable-action, support, refactor]
created: 2026-07-12
updated: 2026-10-22
qmd: "Sixteen theme no app Support FrontofficeUrl BlockCategoryRegistry Actions"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/372"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/273"
related:
  - ../../../../../../docs/wiki/rules/queueable-action-trait-mandatory.md
  - fo-folio-links-multilingua.md
  - ../../../../../Modules/User/docs/wiki/concepts/no-app-support-queueable-actions.md
status: draft
---

# no `app/Support/` — theme Actions

## Scopo

Nel tema Sixteen **non** esiste più `app/Support/`. Utility URL e registry blocchi → `app/Actions/` + costanti in `app/Datas/`.

## Migrazione (2026-07-12)

| Legacy `app/Support/` | Destinazione |
|----------------------|--------------|
| `FrontofficeUrl::path` | `Actions/Url/BuildLocalizedFrontofficePathAction` |
| `FrontofficeUrl::fromStoredUrl` | `Actions/Url/NormalizeStoredFrontofficeUrlAction` |
| `FrontofficeUrl::testsParity` | `Actions/Url/BuildFrontofficeTestsParityPathAction` |
| `BlockCategoryRegistry` (liste) | `Datas/BlockCategoryRegistryData` |
| `BlockCategoryRegistry` (scan/is*) | `Actions/Block/*` |

## Perché

- Tema = bridge FO: stesso contratto QueueableAction dei moduli
- Modelli municipal chiamano `app(BuildLocalizedFrontofficePathAction::class)->execute($path)`
- Header nav CMS usa callback `$headerFolioUrl` (non path hardcoded)

## Collegamenti

- [fo-folio-links-multilingua.md](fo-folio-links-multilingua.md)
- [cms-block-naming-tailwind-flowbite.md](../rules/cms-block-naming-tailwind-flowbite.md)

## Wave FO 2026-10-22

URL/alias/search ex-Support → `Infrastructure\Url\*` + `Presentation\Search\*` +
`Actions\Folio\ResolveContainer0IndexAction`. Canon:
[reusable-components.md](../../rules/reusable-components.md) ·
[second-brain process](../../../../../../docs/second-brain/process/no-app-support-queueable-actions.md).
