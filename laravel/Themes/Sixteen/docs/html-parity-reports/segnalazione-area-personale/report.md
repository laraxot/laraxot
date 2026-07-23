# HTML Structure Comparison Report

**Generated**: 2026-04-11 22:36:47
**URL1 (Reference)**: file:///tmp/batch-fix/ref_segnalazione-area-personale.html
**URL2 (Local)**:     file:///tmp/batch-fix/local_segnalazione-area-personale.html

---

## Summary

| Metric                        | Value          |
|-------------------------------|----------------|
| Structure lines (URL1)        | 886        |
| Structure lines (URL2)        | 891        |
| Lines only in URL1            | 503      |
| Lines only in URL2            | 508      |
| Structural similarity          | 43.1%    |
| Total elements (URL1)         | 886 |
| Total elements (URL2)         | 891 |
| data-element attrs (URL1)     | 16 |
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
| Elements with data-element | 16 | 15 |

---

## Element Tag Counts

| Tag | URL1 | URL2 | Delta |
|-----|------|------|-------|
| `<a>` | 134 | 135 | +1 |
| `<br>` | 8 | 8 | 0 |
| `<button>` | 34 | 35 | +1 |
| `<date>` | 7 | 8 | +1 |
| `<div>` | 253 | 251 | -2 |
| `<footer>` | 1 | 1 | 0 |
| `<form>` | 1 | 2 | +1 |
| `<h1>` | 1 | 1 | 0 |
| `<h2>` | 8 | 10 | +2 |
| `<h3>` | 6 | 7 | +1 |
| `<h4>` | 6 | 6 | 0 |
| `<h5>` | 1 | 2 | +1 |
| `<header>` | 1 | 1 | 0 |
| `<image>` | 1 | 1 | 0 |
| `<img>` | 10 | 9 | -1 |
| `<input>` | 3 | 4 | +1 |
| `<label>` | 3 | 4 | +1 |
| `<li>` | 103 | 103 | 0 |
| `<link>` | 0 | 3 | +3 |
| `<main>` | 1 | 1 | 0 |
| `<nav>` | 5 | 5 | 0 |
| `<ol>` | 1 | 1 | 0 |
| `<p>` | 22 | 23 | +1 |
| `<section>` | 2 | 5 | +3 |
| `<span>` | 130 | 131 | +1 |
| `<svg>` | 58 | 53 | -5 |
| `<ul>` | 29 | 29 | 0 |
| `<use>` | 57 | 52 | -5 |

---

## Unified Diff (first 100 lines)

```diff
--- URL1: file:///tmp/batch-fix/ref_segnalazione-area-personale.html
+++ URL2: file:///tmp/batch-fix/local_segnalazione-area-personale.html
@@ -27,37 +27,11 @@
                           <li>
                             <a class="dropdown-item list-item">
                               <span>
-              <div class="it-user-wrapper nav-item dropdown">
-                <a class="btn btn-primary btn-icon btn-full">
-                  <span class="rounded-icon">
-                    <img class="border rounded-circle icon-white">
-                  <span class="d-none d-lg-block">
-                  <svg class="icon icon-white d-none d-lg-block">
+              <a class="btn btn-primary btn-icon btn-full" data-element="personal-area-login">
+                <span class="rounded-icon">
+                  <svg class="icon icon-primary">
                     <use>
-                <div class="dropdown-menu">
-                  <div class="row">
-                    <div class="col-12">
-                      <div class="link-list-wrapper">
-                        <ul class="link-list">
-                          <li>
-                            <a class="dropdown-item list-item">
-                              <span>
-                          <li>
-                            <a class="dropdown-item list-item">
-                              <span>
-                          <li>
-                            <a class="dropdown-item list-item">
-                              <span>
-                          <li>
-                            <span class="divider">
-                          <li>
-                            <a class="dropdown-item list-item">
-                              <span>
-                          <li>
-                            <a class="list-item left-icon">
-                              <svg class="icon icon-primary icon-sm left">
-                                <use>
-                              <span class="fw-bold">
+                <span class="d-none d-lg-block">
   <div class="it-nav-wrapper">
     <div class="it-header-center-wrapper">
       <div class="container">
@@ -192,490 +166,519 @@
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
-              <li class="breadcrumb-item active">
-        <div class="cmp-heading pb-3 pb-lg-4">
-          <h1 class="title-xxxlarge">
-          <p class="subtitle-small">
-      <div class="col-12 p-0">
-        <div class="cmp-nav-tab mb-4 mb-lg-5 mt-lg-4">
-          <ul id="myTab" class="nav nav-tabs nav-tabs-icon-text w-100 flex-nowrap" role="tablist">
-            <li class="nav-item w-100" role="tab">
-              <a class="nav-link justify-content-start text-tab active" role="button">
-                <svg class="icon me-1 mr-lg-10">
-                  <use>
-            <li class="nav-item w-100" role="tab">
-              <a class="nav-link justify-content-start text-tab" role="button">
-                <svg class="icon me-1 mr-lg-10">
-                  <use>
-            <li class="nav-item w-100" role="tab">
-              <a class="nav-link justify-content-start text-tab" role="button">
-                <svg class="icon me-1 mr-lg-10">
-                  <use>
-            <li class="nav-item w-100" role="tab">
-              <a class="nav-link justify-content-start text-tab" role="button">
-                <svg class="icon me-1 mr-lg-10">
-                  <use>
-    <div class="it-page-sections-container">
-      <div class="tab-content">
-        <div id="data-ex-tab1" class="tab-pane fade show active" role="tabpanel">
-          <div class="row">
-            <div class="col-12 col-lg-3 d-lg-block mb-4 d-none">
-              <div class="cmp-navscroll sticky-top">
-                <nav class="navbar it-navscroll-wrapper navbar-expand-lg">
-                  <div id="navbarNavProgress" class="navbar-custom">
-                    <div class="menu-wrapper">
-                      <div class="link-list-wrapper">
-                        <div class="accordion">
-                          <div class="accordion-item">
-                            <span id="accordion-title-one" class="accordion-header">
-                              <button class="accordion-button pb-10 px-3">
-                                <svg class="icon icon-xs right">
-                                  <use>
-                            <div class="progress">
-                              <div class="progress-bar it-navscroll-progressbar" role="progressbar">
-                            <div id="collapse-one" class="accordion-collapse collapse show" role="region">
-                              <div class="accordion-body">
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

