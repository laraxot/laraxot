---
title: MergeTranslationsTrait archiviato — duplicato di merge_translation_files()
type: decision
tags: [trait, archive, duplicate, translations]
created: 2026-07-14
---

# MergeTranslationsTrait archiviato

`laravel/app/Traits/MergeTranslationsTrait.php` (root, zero utilizzatori)
duplicava l'helper globale già esistente `merge_translation_files()`
(`Modules/Xot/helpers/Helper.php:303`, stub PHPStan in
`Modules/Xot/helpers/merge_translation_files.stub.php`), già usato in
`Modules/Xot/lang/it/set_default_tenant_for_urls_fields*.php`.

## Azione

Spostato e rinominato in
`Modules/Xot/app/Traits/MergeTranslationsTrait.php.old` (suffisso `.old`,
non cancellato).

## Perché

Un solo punto di verità per il merge dei file di traduzione: la funzione
helper globale, non un trait duplicato con la stessa logica
(`array_merge` su una lista di array). Riusare `merge_translation_files()`
invece di reintrodurre il trait.
