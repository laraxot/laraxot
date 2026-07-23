# HTML Structure Comparison Report

**Generated**: 2026-04-11 22:36:48
**URL1 (Reference)**: file:///tmp/batch-fix/ref_segnalazione-01-privacy.html
**URL2 (Local)**:     file:///tmp/batch-fix/local_segnalazione-01-privacy.html

---

## Summary

| Metric                        | Value          |
|-------------------------------|----------------|
| Structure lines (URL1)        | 430        |
| Structure lines (URL2)        | 434        |
| Lines only in URL1            | 67      |
| Lines only in URL2            | 71      |
| Structural similarity          | 84.0%    |
| Total elements (URL1)         | 430 |
| Total elements (URL2)         | 434 |
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

### Missing Tags in URL2
- ❌ `<article>`
- ❌ `<aside>`
- ❌ `<section>`

---

## data-element Attributes (AGID Compliance)

| Metric | URL1 | URL2 |
|--------|------|------|
| Elements with data-element | 15 | 15 |

---

## Element Tag Counts

| Tag | URL1 | URL2 | Delta |
|-----|------|------|-------|
| `<a>` | 86 | 86 | 0 |
| `<br>` | 8 | 8 | 0 |
| `<button>` | 8 | 8 | 0 |
| `<div>` | 94 | 95 | +1 |
| `<footer>` | 1 | 1 | 0 |
| `<form>` | 1 | 1 | 0 |
| `<h1>` | 1 | 1 | 0 |
| `<h2>` | 3 | 3 | 0 |
| `<h4>` | 6 | 6 | 0 |
| `<header>` | 1 | 1 | 0 |
| `<image>` | 1 | 1 | 0 |
| `<img>` | 1 | 1 | 0 |
| `<input>` | 2 | 2 | 0 |
| `<label>` | 2 | 2 | 0 |
| `<li>` | 79 | 79 | 0 |
| `<link>` | 0 | 3 | +3 |
| `<main>` | 1 | 1 | 0 |
| `<nav>` | 3 | 3 | 0 |
| `<ol>` | 1 | 1 | 0 |
| `<p>` | 3 | 3 | 0 |
| `<span>` | 45 | 45 | 0 |
| `<svg>` | 34 | 34 | 0 |
| `<ul>` | 16 | 16 | 0 |
| `<use>` | 33 | 33 | 0 |

---

## Unified Diff (first 100 lines)

```diff
--- URL1: file:///tmp/batch-fix/ref_segnalazione-01-privacy.html
+++ URL2: file:///tmp/batch-fix/local_segnalazione-01-privacy.html
@@ -114,7 +114,7 @@
                         <a class="nav-link" data-element="news">
                           <span>
                       <li class="nav-item">
-                        <a class="nav-link active" data-element="all-services">
+                        <a class="nav-link" data-element="all-services">
                           <span>
                       <li class="nav-item">
                         <a class="nav-link" data-element="live">
@@ -166,73 +166,74 @@
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
-        <h1 class="title-xxxlarge mb-4">
-      <div class="col-12">
-        <div class="steppers">
-          <div class="steppers-header">
-            <ul>
-              <li class="active">
-                <span class="visually-hidden">
-              <li>
-              <li>
-            <span class="steppers-index">
-  <div class="container">
-    <div class="row justify-content-center">
-      <div class="col-12 col-lg-8 pb-40 pb-lg-80">
-        <p class="text-paragraph mb-lg-4">
-        <p class="text-paragraph mb-0">
-          <a class="t-primary">
-        <div class="form-check mt-4 mb-3 mt-md-40 mb-lg-40">
-          <div class="checkbox-body d-flex align-items-center">
-            <input id="privacy">
-            <label class="title-small-semi-bold pt-1">
-        <button class="btn btn-primary mobile-full">
-          <span>
-  <div class="bg-grey-card shadow-contacts">
+  <div class="tests-view-wrapper">
     <div class="container">
-      <div class="row">
-        <div class="col-12 col-lg-6 offset-lg-3 p-contacts">
-          <div class="cmp-contacts">
-            <div class="card w-100">
-              <div class="card-body">
-                <h2 class="title-medium-2-semi-bold">
-                <ul class="contact-list p-0">
-                  <li>
-                    <a class="list-item">
-                      <svg class="icon icon-primary icon-sm">
-                        <use>
-                      <span>
-                  <li>
-                    <a class="list-item" data-element="contacts">
-                      <svg class="icon icon-primary icon-sm">
-                        <use>
-                      <span>
-                  <li>
-                    <a class="list-item">
-                      <svg class="icon icon-primary icon-sm">
-                        <use>
-                      <span>
-                  <li>
-                    <a class="list-item" data-element="appointment-booking">
-                      <svg class="icon icon-primary icon-sm">
-                        <use>
-                      <span>
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
+    <div class="container">
+      <div class="row justify-content-center">
+        <div class="col-12 col-lg-10">
+          <h1 class="title-xxxlarge mb-4">
+        <div class="col-12">
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

