# HTML Structure Comparison Report

**Generated**: 2026-04-10 22:24:17
**URL1 (Reference)**: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-03-riepilogo.html
**URL2 (Local)**:     http://127.0.0.1:8000/it/tests/segnalazione-03-riepilogo

---

## Summary

| Metric                        | Value          |
|-------------------------------|----------------|
| Structure lines (URL1)        | 523        |
| Structure lines (URL2)        | 533        |
| Lines only in URL1            | 161      |
| Lines only in URL2            | 171      |
| Structural similarity          | 68.6%    |
| Total elements (URL1)         | 523 |
| Total elements (URL2)         | 533 |
| data-element attrs (URL1)     | 15 |
| data-element attrs (URL2)     | 15 |

---

## Semantic ID Analysis

### URL1 (Reference)
- ✅ `#footer`
- ✅ `#main-container`
- ✅ `#search-modal`

### URL2 (Local)
- ✅ `#footer`
- ✅ `#main-container`
- ✅ `#search-modal`

### Missing in URL2
- ❌ `#breadcrumbs-container`
- ❌ `#calendario`
- ❌ `#correlati`
- ❌ `#evidence-section`
- ❌ `#head-section`
- ❌ `#it-main-menu`
- ❌ `#mobile-menu`
- ❌ `#rating`
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
- `<footer>`
- `<header>`
- `<main>`
- `<nav>`
- `<section>`

### Missing Tags in URL2
- ❌ `<article>`
- ❌ `<aside>`

---

## data-element Attributes (AGID Compliance)

| Metric | URL1 | URL2 |
|--------|------|------|
| Elements with data-element | 15 | 15 |

---

## Element Tag Counts

| Tag | URL1 | URL2 | Delta |
|-----|------|------|-------|
| `<a>` | 87 | 89 | +2 |
| `<br>` | 8 | 8 | 0 |
| `<button>` | 14 | 14 | 0 |
| `<div>` | 149 | 153 | +4 |
| `<footer>` | 1 | 1 | 0 |
| `<form>` | 1 | 1 | 0 |
| `<h1>` | 1 | 1 | 0 |
| `<h2>` | 6 | 6 | 0 |
| `<h3>` | 2 | 2 | 0 |
| `<h4>` | 8 | 8 | 0 |
| `<header>` | 1 | 1 | 0 |
| `<image>` | 1 | 1 | 0 |
| `<img>` | 1 | 1 | 0 |
| `<input>` | 1 | 1 | 0 |
| `<label>` | 1 | 1 | 0 |
| `<li>` | 79 | 79 | 0 |
| `<link>` | 0 | 3 | +3 |
| `<main>` | 1 | 1 | 0 |
| `<nav>` | 4 | 4 | 0 |
| `<ol>` | 1 | 1 | 0 |
| `<p>` | 11 | 11 | 0 |
| `<section>` | 0 | 1 | +1 |
| `<span>` | 54 | 54 | 0 |
| `<svg>` | 38 | 38 | 0 |
| `<ul>` | 16 | 16 | 0 |
| `<use>` | 37 | 37 | 0 |

---

## Unified Diff (first 100 lines)

```diff
--- URL1: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-03-riepilogo.html
+++ URL2: http://127.0.0.1:8000/it/tests/segnalazione-03-riepilogo
@@ -114,7 +114,7 @@
                         <a class="nav-link" data-element="news">
                           <span>
                       <li class="nav-item">
-                        <a class="nav-link active" data-element="all-services">
+                        <a class="nav-link" data-element="all-services">
                           <span>
                       <li class="nav-item">
                         <a class="nav-link" data-element="live">
@@ -166,166 +166,173 @@
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
-        <div class="cmp-heading pb-3 pb-lg-4">
-          <h1 class="title-xxxlarge">
-      <div class="col-12">
-        <div class="steppers">
-          <div class="steppers-header">
-            <ul>
-              <li class="confirmed">
-                <svg class="icon steppers-success">
-                  <use>
-                <span class="visually-hidden">
-              <li class="confirmed">
-                <svg class="icon steppers-success">
-                  <use>
-                <span class="visually-hidden">
-              <li class="active">
-                <span class="visually-hidden">
-            <span class="steppers-index">
-    <div class="row justify-content-center">
-      <div class="col-12 col-lg-8">
-        <div class="callout callout-highlight ps-3 warning">
-          <div class="callout-title mb-20 d-flex align-items-center">
-            <svg class="icon icon-sm">
-              <use>
-            <span>
-          <p class="titillium text-paragraph">
-            <span class="d-lg-block">
-        <h2 class="title-xxlarge mb-4 mt-40">
-        <div class="cmp-card mb-4">
-          <div class="card has-bkg-grey shadow-sm mb-0">
-            <div class="card-header border-0 p-0 mb-lg-20 m-0">
-              <div class="d-flex">
-                <h3 class="subtitle-large mb-4">
-            <div class="card-body p-0">
-              <div class="cmp-info-summary bg-white p-3 p-lg-4">
-                <div class="card">
-                  <div class="card-header border-bottom border-light p-0 mb-0 pb-2 d-flex justify-content-end">
-                    <a class="text-decoration-none">
-                      <span class="text-button-sm-semi t-primary">
-                  <div class="card-body p-0">
-                    <div class="single-line-info border-light">
-                      <div class="text-paragraph-small">
-                      <div class="border-light">
-                        <p class="data-text">
-                    <div class="single-line-info border-light">
-                      <div class="text-paragraph-small">
-                      <div class="border-light">
-                        <p class="data-text">
-                    <div class="single-line-info border-light">
-                      <div class="text-paragraph-small">
-                      <div class="border-light">
-                        <p class="data-text">
-                    <div class="single-line-info border-light">
-                      <div class="text-paragraph-small">
-                      <div class="border-light">
-                        <p class="data-text">
-                    <div class="single-line-info border-light">
-                      <div class="text-paragraph-small">
-                      <div class="border-light">
-                        <p class="data-text">
-                  <div class="card-footer p-0">
-        <h2 class="title-xxlarge mb-4 mt-40">
-        <div class="cmp-card mb-4">
-          <div class="card has-bkg-grey shadow-sm mb-0">
-            <div class="card-header border-0 p-0 mb-lg-20 m-0">
-              <div class="d-flex">
-                <h3 class="subtitle-large mb-4">
-            <div class="card-body p-0">
-              <div class="cmp-info-summary bg-white mb-4 mb-lg-30 p-3 p-lg-4">
-                <div class="card">
-                  <div class="card-header border-bottom border-light p-0 mb-0 d-flex justify-content-between d-flex justify-content-end">
-                    <h4 class="title-large-semi-bold mb-3">
-                  <div class="card-body p-0">
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

