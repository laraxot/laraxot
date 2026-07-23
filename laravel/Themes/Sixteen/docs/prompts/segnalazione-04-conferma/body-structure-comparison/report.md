# HTML Structure Comparison Report

**Generated**: 2026-04-08 17:09:03
**URL1 (Reference)**: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-04-conferma.html
**URL2 (Local)**:     http://127.0.0.1:8000/it/tests/segnalazione-04-conferma

---

## Summary

| Metric                        | Value          |
|-------------------------------|----------------|
| Structure lines (URL1)        | 551        |
| Structure lines (URL2)        | 223        |
| Lines only in URL1            | 375      |
| Lines only in URL2            | 47      |
| Structural similarity          | 45.5%    |
| Total elements (URL1)         | 551 |
| Total elements (URL2)         | 223 |
| data-element attrs (URL1)     | 37 |
| data-element attrs (URL2)     | 7 |

---

## Semantic ID Analysis

### URL1 (Reference)
- ✅ `#footer`
- ✅ `#main-container`
- ✅ `#rating`
- ✅ `#search-modal`

### URL2 (Local)
- _(none found)_

### Missing in URL2
- ❌ `#breadcrumbs-container`
- ❌ `#calendario`
- ❌ `#correlati`
- ❌ `#evidence-section`
- ❌ `#footer`
- ❌ `#head-section`
- ❌ `#it-main-menu`
- ❌ `#main-container`
- ❌ `#mobile-menu`
- ❌ `#rating`
- ❌ `#search-modal`
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
- `<header>`
- `<main>`
- `<nav>`
- `<section>`

### Missing Tags in URL2
- ❌ `<article>`
- ❌ `<aside>`
- ❌ `<footer>`

---

## data-element Attributes (AGID Compliance)

| Metric | URL1 | URL2 |
|--------|------|------|
| Elements with data-element | 37 | 7 |

---

## Element Tag Counts

| Tag | URL1 | URL2 | Delta |
|-----|------|------|-------|
| `<a>` | 88 | 28 | -60 |
| `<br>` | 9 | 0 | -9 |
| `<button>` | 10 | 11 | +1 |
| `<div>` | 128 | 45 | -83 |
| `<fieldset>` | 4 | 0 | -4 |
| `<footer>` | 1 | 0 | -1 |
| `<form>` | 1 | 0 | -1 |
| `<h1>` | 1 | 0 | -1 |
| `<h2>` | 6 | 0 | -6 |
| `<h3>` | 3 | 0 | -3 |
| `<h4>` | 6 | 0 | -6 |
| `<header>` | 1 | 1 | 0 |
| `<hr>` | 1 | 0 | -1 |
| `<image>` | 1 | 1 | 0 |
| `<img>` | 1 | 0 | -1 |
| `<input>` | 17 | 0 | -17 |
| `<label>` | 17 | 0 | -17 |
| `<legend>` | 4 | 0 | -4 |
| `<li>` | 77 | 26 | -51 |
| `<main>` | 1 | 1 | 0 |
| `<nav>` | 3 | 3 | 0 |
| `<ol>` | 1 | 0 | -1 |
| `<p>` | 5 | 0 | -5 |
| `<path>` | 10 | 0 | -10 |
| `<section>` | 0 | 1 | +1 |
| `<small>` | 1 | 0 | -1 |
| `<span>` | 58 | 47 | -11 |
| `<strong>` | 2 | 0 | -2 |
| `<svg>` | 42 | 27 | -15 |
| `<ul>` | 16 | 6 | -10 |
| `<use>` | 36 | 26 | -10 |

---

## Unified Diff (first 100 lines)

```diff
--- URL1: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-04-conferma.html
+++ URL2: http://127.0.0.1:8000/it/tests/segnalazione-04-conferma
@@ -114,7 +114,7 @@
                         <a class="nav-link" data-element="news">
                           <span>
                       <li class="nav-item">
-                        <a class="nav-link active" data-element="all-services">
+                        <a class="nav-link" data-element="all-services">
                           <span>
                       <li class="nav-item">
                         <a class="nav-link" data-element="live">
@@ -166,386 +166,58 @@
                             <use>
                           <span class="visually-hidden">
 <main>
-  <div id="main-container" class="container">
-    <div class="row justify-content-center">
-      <div class="col-12 col-lg-10">
-        <div class="cmp-breadcrumbs" role="navigation">
-          <nav class="breadcrumb-container">
-            <ol class="breadcrumb p-0" data-element="breadcrumb">
-              <li class="breadcrumb-item">
-                <a>
-                <span class="separator">
-              <li class="breadcrumb-item">
-                <a>
-                <span class="separator">
-              <li class="breadcrumb-item active">
-        <div class="cmp-heading p-0">
-          <div class="categoryicon-top d-flex">
-            <svg class="icon icon-success mr-10 icon-md">
-              <use>
-            <h1 class="title-xxxlarge">
-          <p class="subtitle-small">
-            <strong>
-          <p class="subtitle-small">
-            <a class="t-primary">
-          <p class="subtitle-small pt-3 pt-lg-4">
-            <br>
-            <strong>
-          <button class="btn btn-outline-primary bg-white fw-bold">
-            <span class="rounded-icon">
-              <svg class="icon icon-primary icon-sm">
-                <use>
-            <span>
-        <p class="mt-3">
-          <a class="t-primary text-paragraph">
-          <span class="text-paragraph">
-    <hr class="d-none d-lg-block mt-40 mb-0">
-    <div class="row justify-content-center mb-3 mb-md-5">
-      <div class="col-12 col-lg-10">
-        <div class="cmp-icon-list">
-          <h2 id="related-service" class="title-xxlarge mt-40 mb-2 mb-lg-4 mb-lg-4">
-          <div class="link-list-wrapper">
-            <ul class="link-list">
-              <li class="shadow mb-4">
-                <a class="list-item icon-left t-primary title-small-semi-bold">
-                  <span class="list-item-title-icon-wrapper">
-                    <svg class="icon icon-sm align-self-start icon-color">
-                      <use>
-                    <span class="list-item-title title-small-semi-bold">
-  <div class="bg-primary">
-    <div class="container">
-      <div class="row d-flex justify-content-center bg-primary">
-        <div class="col-12 col-lg-6 p-lg-0 px-4">
-          <div id="rating" class="cmp-rating pt-lg-80 pb-lg-80">
-            <div class="card shadow card-wrapper" data-element="feedback">
-              <div class="cmp-rating__card-first">
-                <div class="card-header border-0">
-                  <h2 class="title-medium-2-semi-bold mb-0" data-element="feedback-title">
-                <div class="card-body">
-                  <fieldset class="rating">
-                    <legend class="visually-hidden">
-                    <input id="star5a">
-                    <label class="full rating-star active" data-element="feedback-rate-5">
-                      <svg class="icon icon-sm" role="img">
-                        <path>
-                        <path>
-                      <span id="first-star" class="visually-hidden">
-                    <input id="star4a">
-                    <label class="full rating-star active" data-element="feedback-rate-4">
-                      <svg class="icon icon-sm" role="img">
-                        <path>
-                        <path>
-                      <span id="second-star" class="visually-hidden">
-                    <input id="star3a">
-                    <label class="full rating-star active" data-element="feedback-rate-3">
-                      <svg class="icon icon-sm" role="img">
-                        <path>
-                        <path>
-                      <span id="third-star" class="visually-hidden">
-                    <input id="star2a">
-                    <label class="full rating-star active" data-element="feedback-rate-2">
-                      <svg class="icon icon-sm" role="img">
-                        <path>
-                        <path>
-                      <span id="fourth-star" class="visually-hidden">
-                    <input id="star1a">
-                    <label class="full rating-star active" data-element="feedback-rate-1">
-                      <svg class="icon icon-sm" role="img">
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

