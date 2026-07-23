# HTML Structure Comparison Report

**Generated**: 2026-04-11 22:36:50
**URL1 (Reference)**: file:///tmp/batch-fix/ref_segnalazione-04-conferma.html
**URL2 (Local)**:     file:///tmp/batch-fix/local_segnalazione-04-conferma.html

---

## Summary

| Metric                        | Value          |
|-------------------------------|----------------|
| Structure lines (URL1)        | 551        |
| Structure lines (URL2)        | 577        |
| Lines only in URL1            | 183      |
| Lines only in URL2            | 209      |
| Structural similarity          | 65.2%    |
| Total elements (URL1)         | 551 |
| Total elements (URL2)         | 577 |
| data-element attrs (URL1)     | 37 |
| data-element attrs (URL2)     | 36 |

---

## Semantic ID Analysis

### URL1 (Reference)
- ✅ `#footer`
- ✅ `#main-container`
- ✅ `#rating`
- ✅ `#search-modal`

### URL2 (Local)
- ✅ `#footer`
- ✅ `#main-container`
- ✅ `#rating`
- ✅ `#search-modal`

### Missing in URL2
- ❌ `#breadcrumbs-container`
- ❌ `#calendario`
- ❌ `#correlati`
- ❌ `#evidence-section`
- ❌ `#head-section`
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
- `<footer>`
- `<header>`
- `<main>`
- `<nav>`

### Missing Tags in URL2
- ❌ `<article>`
- ❌ `<aside>`
- ❌ `<section>`

---

## data-element Attributes (AGID Compliance)

| Metric | URL1 | URL2 |
|--------|------|------|
| Elements with data-element | 37 | 36 |

---

## Element Tag Counts

| Tag | URL1 | URL2 | Delta |
|-----|------|------|-------|
| `<a>` | 88 | 87 | -1 |
| `<br>` | 9 | 9 | 0 |
| `<button>` | 10 | 9 | -1 |
| `<div>` | 128 | 142 | +14 |
| `<fieldset>` | 4 | 3 | -1 |
| `<footer>` | 1 | 1 | 0 |
| `<form>` | 1 | 1 | 0 |
| `<h1>` | 1 | 2 | +1 |
| `<h2>` | 6 | 7 | +1 |
| `<h3>` | 3 | 2 | -1 |
| `<h4>` | 6 | 6 | 0 |
| `<header>` | 1 | 1 | 0 |
| `<hr>` | 1 | 1 | 0 |
| `<image>` | 1 | 1 | 0 |
| `<img>` | 1 | 1 | 0 |
| `<input>` | 17 | 17 | 0 |
| `<label>` | 17 | 17 | 0 |
| `<legend>` | 4 | 3 | -1 |
| `<li>` | 77 | 78 | +1 |
| `<link>` | 0 | 3 | +3 |
| `<main>` | 1 | 1 | 0 |
| `<nav>` | 3 | 2 | -1 |
| `<ol>` | 1 | 0 | -1 |
| `<p>` | 5 | 7 | +2 |
| `<path>` | 10 | 10 | 0 |
| `<small>` | 1 | 1 | 0 |
| `<span>` | 58 | 60 | +2 |
| `<strong>` | 2 | 2 | 0 |
| `<svg>` | 42 | 46 | +4 |
| `<ul>` | 16 | 17 | +1 |
| `<use>` | 36 | 40 | +4 |

---

## Unified Diff (first 100 lines)

```diff
--- URL1: file:///tmp/batch-fix/ref_segnalazione-04-conferma.html
+++ URL2: file:///tmp/batch-fix/local_segnalazione-04-conferma.html
@@ -114,7 +114,7 @@
                         <a class="nav-link" data-element="news">
                           <span>
                       <li class="nav-item">
-                        <a class="nav-link active" data-element="all-services">
+                        <a class="nav-link" data-element="all-services">
                           <span>
                       <li class="nav-item">
                         <a class="nav-link" data-element="live">
@@ -166,194 +166,217 @@
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
+  <div class="tests-view-wrapper">
+    <div class="cmp-heading pb-3 pb-lg-4">
+      <div class="categoryicon-top d-flex">
+        <svg class="icon icon-primary mr-10 icon-md">
+          <use>
+        <h1 class="title-xxxlarge">
+      <p class="subtitle-small">
+    <div class="col-12">
+      <div class="steppers">
+        <div class="steppers-header">
+          <ul>
+            <li class="confirmed">
+              <svg class="icon steppers-success">
                 <use>
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

