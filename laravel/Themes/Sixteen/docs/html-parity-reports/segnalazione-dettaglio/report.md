# HTML Structure Comparison Report

**Generated**: 2026-04-11 22:36:48
**URL1 (Reference)**: file:///tmp/batch-fix/ref_segnalazione-dettaglio.html
**URL2 (Local)**:     file:///tmp/batch-fix/local_segnalazione-dettaglio.html

---

## Summary

| Metric                        | Value          |
|-------------------------------|----------------|
| Structure lines (URL1)        | 804        |
| Structure lines (URL2)        | 816        |
| Lines only in URL1            | 430      |
| Lines only in URL2            | 442      |
| Structural similarity          | 46.2%    |
| Total elements (URL1)         | 804 |
| Total elements (URL2)         | 816 |
| data-element attrs (URL1)     | 45 |
| data-element attrs (URL2)     | 43 |

---

## Semantic ID Analysis

### URL1 (Reference)
- ✅ `#footer`
- ✅ `#main-container`
- ✅ `#rating`
- ✅ `#search-modal`

### URL2 (Local)
- ✅ `#footer`
- ✅ `#rating`
- ✅ `#search-modal`

### Missing in URL2
- ❌ `#breadcrumbs-container`
- ❌ `#calendario`
- ❌ `#correlati`
- ❌ `#evidence-section`
- ❌ `#head-section`
- ❌ `#it-main-menu`
- ❌ `#main-container`
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
- `<section>`

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
| Elements with data-element | 45 | 43 |

---

## Element Tag Counts

| Tag | URL1 | URL2 | Delta |
|-----|------|------|-------|
| `<a>` | 121 | 120 | -1 |
| `<br>` | 14 | 10 | -4 |
| `<button>` | 16 | 16 | 0 |
| `<div>` | 186 | 193 | +7 |
| `<fieldset>` | 4 | 3 | -1 |
| `<footer>` | 1 | 1 | 0 |
| `<form>` | 1 | 1 | 0 |
| `<h1>` | 1 | 1 | 0 |
| `<h2>` | 15 | 16 | +1 |
| `<h3>` | 3 | 2 | -1 |
| `<h4>` | 6 | 6 | 0 |
| `<header>` | 1 | 1 | 0 |
| `<hr>` | 1 | 1 | 0 |
| `<image>` | 1 | 1 | 0 |
| `<img>` | 2 | 2 | 0 |
| `<input>` | 17 | 17 | 0 |
| `<label>` | 17 | 17 | 0 |
| `<legend>` | 4 | 3 | -1 |
| `<li>` | 115 | 115 | 0 |
| `<link>` | 0 | 3 | +3 |
| `<main>` | 1 | 1 | 0 |
| `<nav>` | 4 | 4 | 0 |
| `<ol>` | 1 | 1 | 0 |
| `<p>` | 13 | 24 | +11 |
| `<path>` | 10 | 10 | 0 |
| `<section>` | 9 | 9 | 0 |
| `<small>` | 3 | 3 | 0 |
| `<span>` | 104 | 102 | -2 |
| `<svg>` | 56 | 56 | 0 |
| `<ul>` | 27 | 27 | 0 |
| `<use>` | 50 | 50 | 0 |

---

## Unified Diff (first 100 lines)

```diff
--- URL1: file:///tmp/batch-fix/ref_segnalazione-dettaglio.html
+++ URL2: file:///tmp/batch-fix/local_segnalazione-dettaglio.html
@@ -114,7 +114,7 @@
                         <a class="nav-link" data-element="news">
                           <span>
                       <li class="nav-item">
-                        <a class="nav-link active" data-element="all-services">
+                        <a class="nav-link" data-element="all-services">
                           <span>
                       <li class="nav-item">
                         <a class="nav-link" data-element="live">
@@ -166,447 +166,456 @@
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
-  <div class="container">
-    <div class="row justify-content-center">
-      <div class="col-12 col-lg-10">
-        <div class="cmp-heading pb-3 pb-lg-4">
-          <div class="row">
-            <div class="col-lg-8">
-              <h1 class="title-xxxlarge" data-element="service-title">
-              <ul class="d-flex flex-wrap gap-1 my-3">
-                <li>
-                  <div class="chip chip-simple text-button" data-element="service-status">
-                    <span class="chip-label">
-              <p class="subtitle-small mb-3">
-              <div class="d-lg-flex gap-30 mb-2">
-                <button class="btn fw-bold btn-primary mr-lg-30">
-                  <span>
-                <button class="btn fw-bold btn-outline-primary t-primary">
-                  <span>
-            <div class="col-lg-3 offset-lg-1 mt-5 mt-lg-0">
-              <div class="dropdown">
-                <button id="shareActions" class="btn btn-dropdown dropdown-toggle text-decoration-underline d-inline-flex align-items-center fs-0">
-                  <svg class="icon">
-                    <use>
-                  <small>
-                <div class="dropdown-menu shadow-lg">
-                  <div class="link-list-wrapper">
-                    <ul class="link-list" role="menu">
-                      <li role="none">
-                        <a class="list-item" role="menuitem">
-                          <svg class="icon">
-                            <use>
-                          <span>
-                      <li role="none">
-                        <a class="list-item" role="menuitem">
-                          <svg class="icon">
-                            <use>
-                          <span>
-                      <li role="none">
-                        <a class="list-item" role="menuitem">
-                          <svg class="icon">
-                            <use>
-                          <span>
-                      <li role="none">
-                        <a class="list-item" role="menuitem">
-                          <svg class="icon">
-                            <use>
-                          <span>
-              <div class="dropdown">
-                <button id="viewActions" class="btn btn-dropdown dropdown-toggle text-decoration-underline d-inline-flex align-items-center fs-0">
-                  <svg class="icon">
-                    <use>
-                  <small>
-                <div class="dropdown-menu shadow-lg">
-                  <div class="link-list-wrapper">
-                    <ul class="link-list" role="menu">
-                      <li role="none">
-                        <a class="list-item" role="menuitem">
-                          <svg class="icon">
-                            <use>
-                          <span>
-                      <li role="none">
-                        <a class="list-item" role="menuitem">
-                          <svg class="icon">
-                            <use>
-                          <span>
-                      <li role="none">
-                        <a class="list-item" role="menuitem">
-                          <svg class="icon">
-                            <use>
-                          <span>
-      <hr class="d-none d-lg-block mb-0 mt-2">
-  <div class="container">
-    <div class="row row-column-menu-left mt-lg-80 mt-3">
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

