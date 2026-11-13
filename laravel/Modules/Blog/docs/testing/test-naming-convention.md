---
title: "test naming convention"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "test naming convention"
issues: []
discussions: []
status: draft
---

# Convenzione Naming File Test - Blog Module

**Modulo:** Blog  
**Data:** 10 Ottobre 2025  
**Categoria:** Testing Standards

## 🎯 Regola

**File test DEVONO seguire PascalCase**

## ✅ Pattern Blog Module

```bash
ArticleTest.php
CategoryTest.php
CommentTest.php
TagTest.php
```

## 📋 Checklist

- [ ] PascalCase obbligatorio
- [ ] Suffisso `Test.php` o `Test.pest.php`
- [ ] No duplicati case-insensitive
- [ ] PHPStan Level 10 passa

## PHPStan sui test articolo

Dettaglio fix swarm (factory tipizzate, `published_at`, guard su `fresh()`): [phpstan-article-tests.story](../stories/phpstan-article-tests.story.md).

## 📚 Riferimenti

- [Regola Critica Progetto](../../../../../docs/regole-critiche/test-naming-pascalcase.md)
- [PHPStan Best Practices](../filament/best-practices.md)

---
**Blog Module - Test Naming Standards** ✅

