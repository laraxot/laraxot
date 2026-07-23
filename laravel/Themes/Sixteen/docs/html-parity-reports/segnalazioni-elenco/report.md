# HTML Structure Comparison Report

**Generated**: 2026-04-11 22:36:47
**URL1 (Reference)**: file:///tmp/batch-fix/ref_ticket-list.html
**URL2 (Local)**:     file:///tmp/batch-fix/local_ticket-list.html

---

## Summary

| Metric                        | Value          |
|-------------------------------|----------------|
| Structure lines (URL1)        | 775        |
| Structure lines (URL2)        | 599        |
| Lines only in URL1            | 406      |
| Lines only in URL2            | 230      |
| Structural similarity          | 53.7%    |
| Total elements (URL1)         | 775 |
| Total elements (URL2)         | 599 |
| data-element attrs (URL1)     | 37 |
| data-element attrs (URL2)     | 35 |

---

## Semantic ID Analysis

### URL1 (Reference)
- ✅ `#footer`
- ✅ `#main-container`
- ✅ `#rating`
- ✅ `#search-modal`

### URL2 (Local)
- ✅ `#footer`
- ✅ `#head-section`
- ✅ `#main-container`
- ✅ `#rating`
- ✅ `#search-modal`

### Missing in URL2
- ❌ `#breadcrumbs-container`
- ❌ `#calendario`
- ❌ `#correlati`
- ❌ `#evidence-section`
- ❌ `#it-main-menu`
- ❌ `#mobile-menu`
- ❌ `#useful-links-section`
- ❌ `#valutazione`

---

## Semantic HTML5 Tags

### URL1 (Reference)
- `<footer>`
- `<header>`
- `<main>`
- `<nav>`

### URL2 (Local)
- `<article>`
- `<aside>`
- `<footer>`
- `<header>`
- `<main>`
- `<nav>`
- `<section>`

### Missing Tags in URL2
- _(all semantic tags present)_

---

## data-element Attributes (AGID Compliance)

| Metric | URL1 | URL2 |
|--------|------|------|
| Elements with data-element | 37 | 35 |

---

## Element Tag Counts

| Tag | URL1 | URL2 | Delta |
|-----|------|------|-------|
| `<a>` | 89 | 83 | -6 |
| `<article>` | 0 | 1 | +1 |
| `<aside>` | 0 | 1 | +1 |
| `<br>` | 9 | 9 | 0 |
| `<button>` | 20 | 15 | -5 |
| `<div>` | 261 | 175 | -86 |
| `<fieldset>` | 5 | 4 | -1 |
| `<footer>` | 1 | 1 | 0 |
| `<form>` | 1 | 1 | 0 |
| `<h1>` | 1 | 1 | 0 |
| `<h2>` | 8 | 7 | -1 |
| `<h3>` | 11 | 3 | -8 |
| `<h4>` | 6 | 6 | 0 |
| `<header>` | 1 | 1 | 0 |
| `<hr>` | 1 | 1 | 0 |
| `<image>` | 1 | 1 | 0 |
| `<img>` | 13 | 3 | -10 |
| `<input>` | 28 | 18 | -10 |
| `<label>` | 28 | 18 | -10 |
| `<legend>` | 5 | 4 | -1 |
| `<li>` | 88 | 75 | -13 |
| `<link>` | 0 | 3 | +3 |
| `<main>` | 1 | 1 | 0 |
| `<nav>` | 3 | 3 | 0 |
| `<ol>` | 1 | 1 | 0 |
| `<p>` | 17 | 6 | -11 |
| `<path>` | 10 | 10 | 0 |
| `<section>` | 0 | 4 | +4 |
| `<small>` | 1 | 1 | 0 |
| `<span>` | 66 | 55 | -11 |
| `<svg>` | 44 | 38 | -6 |
| `<ul>` | 17 | 17 | 0 |
| `<use>` | 38 | 32 | -6 |

---

## Unified Diff (first 100 lines)

```diff
--- URL1: file:///tmp/batch-fix/ref_ticket-list.html
+++ URL2: file:///tmp/batch-fix/local_ticket-list.html
@@ -166,418 +166,239 @@
                             <use>
                           <span class="visually-hidden">
 <main>
-  <div id="main-container" class="container">
-    <div class="row justify-content-center mb-md-40 mb-lg-80">
-      <div class="col-12 col-lg-10">
-        <div class="cmp-breadcrumbs" role="navigation">
-          <nav class="breadcrumb-container">
-            <ol class="breadcrumb p-0" data-element="breadcrumb">
-              <li class="breadcrumb-item">
-                <a>
-                <span class="separator">
-              <li class="breadcrumb-item active">
-        <div class="cmp-heading p-0">
-          <h1 class="title-xxxlarge">
-          <p class="subtitle-small">
-      <hr class="d-none d-lg-block mt-30 mb-2">
-    <div class="row justify-content-center">
-      <div class="col-lg-3 d-none d-lg-block">
-        <fieldset>
-          <legend class="h6 text-uppercase category-list__title">
-          <div class="categoy-list pb-4">
-            <ul>
-              <li>
-                <div class="form-check">
-                  <div class="checkbox-body border-light py-1">
-                    <input id="water">
-                    <label class="subtitle-small_semi-bold mb-0 category-list__list">
-              <li>
-                <div class="form-check">
-                  <div class="checkbox-body border-light py-1">
-                    <input id="enviroment">
-                    <label class="subtitle-small_semi-bold mb-0 category-list__list">
-              <li>
-                <div class="form-check">
-                  <div class="checkbox-body border-light py-1">
-                    <input id="street-furniture">
-                    <label class="subtitle-small_semi-bold mb-0 category-list__list">
-              <li>
-                <div class="form-check">
-                  <div class="checkbox-body border-light py-1">
-                    <input id="rodent-control">
-                    <label class="subtitle-small_semi-bold mb-0 category-list__list">
-              <li>
-                <div class="form-check">
-                  <div class="checkbox-body border-light py-1">
-                    <input id="waste">
-                    <label class="subtitle-small_semi-bold mb-0 category-list__list">
-              <li>
-                <div class="form-check">
-                  <div class="checkbox-body border-light py-1">
-                    <input id="maintenance">
-                    <label class="subtitle-small_semi-bold mb-0 category-list__list">
-              <li>
-                <div class="form-check">
-                  <div class="checkbox-body border-light py-1">
-                    <input id="public-order">
-                    <label class="subtitle-small_semi-bold mb-0 category-list__list">
-              <li>
-                <div class="form-check">
-                  <div class="checkbox-body border-light py-1">
-                    <input id="green">
-                    <label class="subtitle-small_semi-bold mb-0 category-list__list">
-              <li>
-                <div class="form-check">
-                  <div class="checkbox-body border-light py-1">
-                    <input id="service">
-                    <label class="subtitle-small_semi-bold mb-0 category-list__list">
-              <li>
-                <div class="form-check">
-                  <div class="checkbox-body border-light py-1">
-                    <input id="security">
-                    <label class="subtitle-small_semi-bold mb-0 category-list__list">
-              <li>
-                <div class="form-check">
-                  <div class="checkbox-body border-light py-1">
-                    <input id="road">
-                    <label class="subtitle-small_semi-bold mb-0 category-list__list">
-      <div class="col-lg-8 offset-lg-1">
-        <div class="d-flex justify-content-between border-bottom border-light pb-3 mt-5">
-          <span class="search-results">
-          <button class="btn p-0 pe-2 d-lg-none">
-            <span class="rounded-icon">
-              <svg class="icon icon-primary icon-xs">
-                <use>
-            <span class="t-primary title-xsmall-semi-bold ms-1">
-          <button class="btn p-0 pe-2 d-none d-lg-block">
-            <span class="title-xsmall-semi-bold ms-1">
-        <ul id="tabDisservizio" class="nav nav-tabs w-100 flex-nowrap border-bottom border-light mb-40 mt-3 shadow-none" role="tablist">
-          <li class="nav-item w-100" role="tab">
-            <a class="nav-link active title-medium-semi-bold pt-0" role="button">
-          <li class="nav-item w-100" role="tab">
-            <a class="nav-link title-medium-semi-bold pt-0" role="button">
-        <div class="tab-content">
-          <div id="data-ex-disservizio1" class="tab-pane fade show active" role="tabpanel">
-            <div class="row">
-              <div class="col-12">
```

---

## Files Generated

| File | Description |
|------|-------------|
| `ref_raw.html` | Raw HTML from URL1 |
| `local_raw.html` | Raw HTML from URL2 |
| `ref_structure.txt` | Indented body structure URL1 |
| `local_structure.txt` | Indented body structure URL2 |
| `ref_elements.json` | Detailed element data URL1 (JSON) |
| `local_elements.json` | Detailed element data URL2 (JSON) |
| `diff.txt` | Unified diff |
| `report.md` | This report |

---

_Generated by: bashscripts/html/compare-html.sh_

