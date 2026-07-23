# HTML Structure Comparison Report

**Generated**: 2026-04-11 22:36:49
**URL1 (Reference)**: file:///tmp/batch-fix/ref_segnalazione-02-dati.html
**URL2 (Local)**:     file:///tmp/batch-fix/local_segnalazione-02-dati.html

---

## Summary

| Metric                        | Value          |
|-------------------------------|----------------|
| Structure lines (URL1)        | 559        |
| Structure lines (URL2)        | 586        |
| Lines only in URL1            | 192      |
| Lines only in URL2            | 219      |
| Structural similarity          | 64.1%    |
| Total elements (URL1)         | 559 |
| Total elements (URL2)         | 586 |
| data-element attrs (URL1)     | 16 |
| data-element attrs (URL2)     | 17 |

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
| Elements with data-element | 16 | 17 |

---

## Element Tag Counts

| Tag | URL1 | URL2 | Delta |
|-----|------|------|-------|
| `<a>` | 91 | 96 | +5 |
| `<br>` | 9 | 9 | 0 |
| `<button>` | 14 | 12 | -2 |
| `<div>` | 147 | 150 | +3 |
| `<footer>` | 1 | 1 | 0 |
| `<form>` | 1 | 1 | 0 |
| `<h1>` | 1 | 1 | 0 |
| `<h2>` | 6 | 6 | 0 |
| `<h3>` | 1 | 1 | 0 |
| `<h4>` | 7 | 7 | 0 |
| `<header>` | 1 | 1 | 0 |
| `<hr>` | 1 | 2 | +1 |
| `<image>` | 1 | 1 | 0 |
| `<img>` | 2 | 3 | +1 |
| `<input>` | 3 | 4 | +1 |
| `<label>` | 6 | 6 | 0 |
| `<li>` | 82 | 85 | +3 |
| `<link>` | 0 | 3 | +3 |
| `<main>` | 1 | 1 | 0 |
| `<nav>` | 5 | 6 | +1 |
| `<ol>` | 1 | 1 | 0 |
| `<option>` | 2 | 4 | +2 |
| `<p>` | 8 | 8 | 0 |
| `<section>` | 3 | 3 | 0 |
| `<select>` | 1 | 1 | 0 |
| `<span>` | 63 | 63 | 0 |
| `<svg>` | 42 | 44 | +2 |
| `<template>` | 0 | 4 | +4 |
| `<textarea>` | 1 | 1 | 0 |
| `<ul>` | 17 | 18 | +1 |
| `<use>` | 41 | 43 | +2 |

---

## Unified Diff (first 100 lines)

```diff
--- URL1: file:///tmp/batch-fix/ref_segnalazione-02-dati.html
+++ URL2: file:///tmp/batch-fix/local_segnalazione-02-dati.html
@@ -114,7 +114,7 @@
                         <a class="nav-link" data-element="news">
                           <span>
                       <li class="nav-item">
-                        <a class="nav-link active" data-element="all-services">
+                        <a class="nav-link" data-element="all-services">
                           <span>
                       <li class="nav-item">
                         <a class="nav-link" data-element="live">
@@ -166,202 +166,226 @@
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
+  <div class="tests-view-wrapper">
+    <div id="main-container" class="container">
+      <div class="row justify-content-center">
+        <div class="col-12 col-lg-10">
+          <div class="cmp-breadcrumbs" role="navigation">
+            <nav class="breadcrumb-container">
+              <ol class="breadcrumb p-0" data-element="breadcrumb">
+                <li class="breadcrumb-item">
+                  <a>
+                  <span class="separator">
+                <li class="breadcrumb-item">
+                  <a>
+                  <span class="separator">
+                <li class="breadcrumb-item active">
+          <div class="cmp-heading pb-3 pb-lg-4">
+            <h1 class="title-xxxlarge">
+        <div class="col-12">
+          <div class="steppers">
+            <div class="steppers-header">
+              <ul>
+                <li class="confirmed">
+                  <svg class="icon steppers-success">
+                    <use>
+                  <span class="visually-hidden">
+                <li class="active">
+                  <span class="visually-hidden">
+                <li>
+              <span class="steppers-index">
+          <p class="title-xsmall d-lg-none my-5">
+          <nav class="cmp-page-index-mobile d-lg-none" data-element="page-index-mobile">
+            <ul class="cmp-page-index-mobile__list">
+              <li>
                 <a>
-                <span class="separator">
-              <li class="breadcrumb-item">
+              <li>
                 <a>
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
-              <li class="active">
-                <span class="visually-hidden">
               <li>
-            <span class="steppers-index">
-        <p class="title-xsmall d-lg-none my-5">
-    <div class="row">
-      <div class="col-12 col-lg-3 d-lg-block mb-4 d-none">
-        <div class="cmp-navscroll sticky-top">
-          <nav class="navbar it-navscroll-wrapper navbar-expand-lg">
-            <div id="navbarNavProgress" class="navbar-custom">
-              <div class="menu-wrapper">
-                <div class="link-list-wrapper">
-                  <div class="accordion">
-                    <div class="accordion-item">
-                      <span id="accordion-title-one" class="accordion-header">
-                        <button class="accordion-button pb-10 px-3">
-                          <svg class="icon icon-xs right">
-                            <use>
-                      <div class="progress">
-                        <div class="progress-bar it-navscroll-progressbar" role="progressbar">
-                      <div id="collapse-one" class="accordion-collapse collapse show" role="region">
-                        <div class="accordion-body">
-                          <ul class="link-list" data-element="page-index">
-                            <li class="nav-item">
-                              <a class="nav-link">
-                                <span>
-                            <li class="nav-item">
-                              <a class="nav-link">
-                                <span>
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

