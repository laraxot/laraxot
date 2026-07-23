# HTML Body Structure Comparison — ticket-list

**Date:** 2026-04-08 09:32
**Reference:** reference.html
**Local:** local.html

---

## 📊 Parity Score

| Metric | Value |
|--------|-------|
| **Overall Parity** | **93.7%** |
| Structure Match | 94.1% |
| Reference elements | 675 |
| Local elements | 655 |

### Severity Breakdown

| Severity | Count | Meaning |
|----------|-------|---------|
| 🔴 BLOCK | 24 | Entire section missing |
| 🟠 FLAG | 16 | Structural mismatch |
| 🟡 WARN | 4 | Attribute/class difference |
| ✅ PASS | 0 | Match confirmed |

### Verdict

**🔴 BLOCKED** — Structural gaps too large — fix HTML before CSS/JS

---

## 🔴 BLOCK — Missing Sections

1. `body > body[0] > header.it-header-wrapper[0] > div.it-header-slim-wrapper[0] > div.container[0] > div.row[0] > div.col-12[0] > div.it-header-slim-wrapper-content[0] > div.it-header-slim-right-zone[0] > a.btn.btn-primary.btn-icon[0] > span.rounded-icon[0]` — Missing section: <span>
2. `body > body[0] > header.it-header-wrapper[0] > div.it-header-slim-wrapper[0] > div.container[0] > div.row[0] > div.col-12[0] > div.it-header-slim-wrapper-content[0] > div.it-header-slim-right-zone[0] > a.btn.btn-primary.btn-icon[0] > span.d-none.d-lg-block[1]` — Missing section: <span>
3. `body > body[0] > header.it-header-wrapper[0] > div.it-nav-wrapper[1] > div.it-header-center-wrapper[0] > div.container[0] > div.row[0] > div.col-12[0] > div.it-header-center-content-wrapper[0] > div.it-right-zone[1] > div.it-search-wrapper[1]` — Missing section: <div>
4. `body > body[0] > header.it-header-wrapper[0] > div.it-nav-wrapper[1] > div.it-header-center-wrapper[0] > div.container[0] > div.row[0] > div.col-12[0] > div.it-header-center-content-wrapper[0] > div.it-right-zone[1]` — Extra section in local: <div>
5. `body > body[0] > header.it-header-wrapper[0] > div.it-nav-wrapper[1] > div#header-nav-wrapper[1] > div.container[0] > div.row[0] > div.col-12[0] > div.navbar.navbar-expand-lg.has-megamenu[0] > div#nav4[0]` — Missing section: <div> #nav4
6. `body > body[0] > main[0] > div#main-container[0] > div.row.justify-content-center[1] > div.col-lg-8.offset-lg-1[1] > div.tab-content[1] > div#data-ex-disservizio2[1] > div.row[0] > div.cmp-card.mb-4.mb-lg-30[0] > div.card.has-bkg-grey.shadow-sm[0] > div.card-body.p-0[0] > div.cmp-info-button-card[0] > div.card.p-3.p-lg-4[0] > div.card-body.p-0[0] > div.accordion-item[0] > div#collapse1[1] > div.accordion-body.p-0[0] > div.cmp-info-summary.bg-white.border-0[0] > div.card[0] > div.card-body.p-0[1] > div.single-line-info.border-light[2] > div.border-light.border-0[1] > div.d-lg-flex.gap-2.mt-3[0] > div[1]` — Missing section: <div>
7. `body > body[0] > main[0] > div#main-container[0] > div.row.justify-content-center[1] > div.col-lg-8.offset-lg-1[1] > div.tab-content[1] > div#data-ex-disservizio2[1] > div.row[0] > div.cmp-card.mb-4.mb-lg-30[0] > div.card.has-bkg-grey.shadow-sm[0] > div.card-body.p-0[0] > div.cmp-info-button-card[0] > div.card.p-3.p-lg-4[0] > div.card-body.p-0[0] > div.accordion-item[0] > div#collapse1[1] > div.accordion-body.p-0[0] > div.cmp-info-summary.bg-white.border-0[0] > div.card[0] > div.card-body.p-0[1] > div.single-line-info.border-light[2] > div.border-light.border-0[1] > div.d-lg-flex.gap-2.mt-3[0] > div[2]` — Missing section: <div>
8. `body > body[0] > main[0] > div#main-container[0] > div.row.justify-content-center[1] > div.col-lg-8.offset-lg-1[1] > div.tab-content[1] > div#data-ex-disservizio2[1] > div.row[0] > div.cmp-card.mb-4.mb-lg-30[1] > div.card.has-bkg-grey.shadow-sm[0] > div.card-body.p-0[0] > div.cmp-info-button-card[0] > div.card.p-3.p-lg-4[0] > div.card-body.p-0[0] > p.card-info[0] > span[0]` — Extra section in local: <span>
9. `body > body[0] > main[0] > div#main-container[0] > div.row.justify-content-center[1] > div.col-lg-8.offset-lg-1[1] > div.tab-content[1] > div#data-ex-disservizio2[1] > div.row[0] > div.cmp-card.mb-4.mb-lg-30[1] > div.card.has-bkg-grey.shadow-sm[0] > div.card-body.p-0[0] > div.cmp-info-button-card[0] > div.card.p-3.p-lg-4[0] > div.card-body.p-0[0] > div.accordion-item[0] > div#collapse2[1] > div.accordion-body.p-0[0] > div.cmp-info-summary.bg-white.border-0[0] > div.card[0] > div.card-body.p-0[1] > div.single-line-info.border-light[2] > div.border-light.border-0[1] > div.d-lg-flex.gap-2.mt-3[0] > div[1]` — Missing section: <div>
10. `body > body[0] > main[0] > div#main-container[0] > div.row.justify-content-center[1] > div.col-lg-8.offset-lg-1[1] > div.tab-content[1] > div#data-ex-disservizio2[1] > div.row[0] > div.cmp-card.mb-4.mb-lg-30[1] > div.card.has-bkg-grey.shadow-sm[0] > div.card-body.p-0[0] > div.cmp-info-button-card[0] > div.card.p-3.p-lg-4[0] > div.card-body.p-0[0] > div.accordion-item[0] > div#collapse2[1] > div.accordion-body.p-0[0] > div.cmp-info-summary.bg-white.border-0[0] > div.card[0] > div.card-body.p-0[1] > div.single-line-info.border-light[2] > div.border-light.border-0[1] > div.d-lg-flex.gap-2.mt-3[0] > div[2]` — Missing section: <div>
11. `body > body[0] > main[0] > div#main-container[0] > div.row.justify-content-center[1] > div.col-lg-8.offset-lg-1[1] > div.tab-content[1] > div#data-ex-disservizio2[1] > div.row[0] > div.cmp-card.mb-4.mb-lg-30[2] > div.card.has-bkg-grey.shadow-sm[0] > div.card-body.p-0[0] > div.cmp-info-button-card[0] > div.card.p-3.p-lg-4[0] > div.card-body.p-0[0] > p.card-info[0] > span[0]` — Extra section in local: <span>
12. `body > body[0] > main[0] > div#main-container[0] > div.row.justify-content-center[1] > div.col-lg-8.offset-lg-1[1] > div.tab-content[1] > div#data-ex-disservizio2[1] > div.row[0] > div.cmp-card.mb-4.mb-lg-30[2] > div.card.has-bkg-grey.shadow-sm[0] > div.card-body.p-0[0] > div.cmp-info-button-card[0] > div.card.p-3.p-lg-4[0] > div.card-body.p-0[0] > div.accordion-item[0] > div#collapse3[1] > div.accordion-body.p-0[0] > div.cmp-info-summary.bg-white.border-0[0] > div.card[0] > div.card-body.p-0[1] > div.single-line-info.border-light[2] > div.border-light.border-0[1] > div.d-lg-flex.gap-2.mt-3[0] > div[1]` — Missing section: <div>
13. `body > body[0] > main[0] > div#main-container[0] > div.row.justify-content-center[1] > div.col-lg-8.offset-lg-1[1] > div.tab-content[1] > div#data-ex-disservizio2[1] > div.row[0] > div.cmp-card.mb-4.mb-lg-30[2] > div.card.has-bkg-grey.shadow-sm[0] > div.card-body.p-0[0] > div.cmp-info-button-card[0] > div.card.p-3.p-lg-4[0] > div.card-body.p-0[0] > div.accordion-item[0] > div#collapse3[1] > div.accordion-body.p-0[0] > div.cmp-info-summary.bg-white.border-0[0] > div.card[0] > div.card-body.p-0[1] > div.single-line-info.border-light[2] > div.border-light.border-0[1] > div.d-lg-flex.gap-2.mt-3[0] > div[2]` — Missing section: <div>
14. `body > body[0] > main[0] > div#main-container[0] > div.row.justify-content-center[1] > div.col-lg-8.offset-lg-1[1] > div.tab-content[1] > div#data-ex-disservizio2[1] > div.row[0] > div.col-12.text-center[3]` — Extra section in local: <div>
15. `body > body[0] > main[0] > div#main-container[0] > div.row.justify-content-center[1] > div.col-lg-8.offset-lg-1[1] > div.tab-content[1] > div#data-ex-disservizio2[1] > div.row[0] > div.col-lg-6.mt-50.mb-4[4]` — Extra section in local: <div>
16. `body > body[0] > main[0] > div#main-container[0] > div.row.justify-content-center[1] > div.col-lg-8.offset-lg-1[1] > div.tab-content[1] > div#data-ex-disservizio2[1] > div.col-12.text-center[1]` — Missing section: <div>
17. `body > body[0] > main[0] > div#main-container[0] > div.row.justify-content-center[1] > div.col-lg-8.offset-lg-1[1] > div.tab-content[1] > div#data-ex-disservizio2[1] > div.col-lg-6.mt-50.mb-4[2]` — Missing section: <div>
18. `body > body[0] > main[0] > div#main-container[0] > div.bg-primary[2]` — Extra section in local: <div>
19. `body > body[0] > main[0] > div#main-container[0] > div#modal-categories[3]` — Extra section in local: <div> #modal-categories
20. `body > body[0] > main[0] > div#main-container[0] > div.it-example-modal[4]` — Extra section in local: <div>
21. `body > body[0] > main[0] > div#main-container[0] > div.bg-grey-card.shadow-contacts[5]` — Extra section in local: <div>
22. `body > body[0] > main[0] > div.bg-primary[1]` — Missing section: <div>
23. `body > body[0] > main[0] > div.bg-grey-card.shadow-contacts[2]` — Missing section: <div>
24. `body > body[0] > main[0] > div.it-example-modal[3]` — Missing section: <div>

---

## 🟠 FLAG — Structural Mismatches

1. `body > body[0] > header.it-header-wrapper[0] > div.it-header-slim-wrapper[0] > div.container[0] > div.row[0] > div.col-12[0] > div.it-header-slim-wrapper-content[0] > div.it-header-slim-right-zone[0] > a.btn.btn-primary.btn-icon[0]` — Children count: ref=2, local=0
2. `body > body[0] > header.it-header-wrapper[0] > div.it-nav-wrapper[1] > div.it-header-center-wrapper[0] > div.container[0] > div.row[0] > div.col-12[0] > div.it-header-center-content-wrapper[0]` — Children count: ref=2, local=3
3. `body > body[0] > header.it-header-wrapper[0] > div.it-nav-wrapper[1] > div.it-header-center-wrapper[0] > div.container[0] > div.row[0] > div.col-12[0] > div.it-header-center-content-wrapper[0] > div.it-brand-wrapper[0]` — Tag mismatch: ref=<div>, local=<button>
4. `body > body[0] > header.it-header-wrapper[0] > div.it-nav-wrapper[1] > div.it-header-center-wrapper[0] > div.container[0] > div.row[0] > div.col-12[0] > div.it-header-center-content-wrapper[0] > div.it-right-zone[1]` — Children count: ref=2, local=1
5. `body > body[0] > header.it-header-wrapper[0] > div.it-nav-wrapper[1] > div.it-header-center-wrapper[0] > div.container[0] > div.row[0] > div.col-12[0] > div.it-header-center-content-wrapper[0] > div.it-right-zone[1] > div.it-socials.d-none.d-lg-flex[0]` — Tag mismatch: ref=<div>, local=<a>
6. `body > body[0] > header.it-header-wrapper[0] > div.it-nav-wrapper[1] > div#header-nav-wrapper[1] > div.container[0] > div.row[0] > div.col-12[0] > div.navbar.navbar-expand-lg.has-megamenu[0]` — Children count: ref=2, local=1
7. `body > body[0] > header.it-header-wrapper[0] > div.it-nav-wrapper[1] > div#header-nav-wrapper[1] > div.container[0] > div.row[0] > div.col-12[0] > div.navbar.navbar-expand-lg.has-megamenu[0] > button.custom-navbar-toggler[0]` — Tag mismatch: ref=<button>, local=<div>
8. `body > body[0] > main[0]` — Children count: ref=4, local=1
9. `body > body[0] > main[0] > div#main-container[0]` — Children count: ref=2, local=6
10. `body > body[0] > main[0] > div#main-container[0] > div.row.justify-content-center[1] > div.col-lg-8.offset-lg-1[1] > div.tab-content[1] > div#data-ex-disservizio2[1]` — Children count: ref=3, local=1
11. `body > body[0] > main[0] > div#main-container[0] > div.row.justify-content-center[1] > div.col-lg-8.offset-lg-1[1] > div.tab-content[1] > div#data-ex-disservizio2[1] > div.row[0]` — Children count: ref=3, local=5
12. `body > body[0] > main[0] > div#main-container[0] > div.row.justify-content-center[1] > div.col-lg-8.offset-lg-1[1] > div.tab-content[1] > div#data-ex-disservizio2[1] > div.row[0] > div.cmp-card.mb-4.mb-lg-30[0] > div.card.has-bkg-grey.shadow-sm[0] > div.card-body.p-0[0] > div.cmp-info-button-card[0] > div.card.p-3.p-lg-4[0] > div.card-body.p-0[0] > div.accordion-item[0] > div#collapse1[1] > div.accordion-body.p-0[0] > div.cmp-info-summary.bg-white.border-0[0] > div.card[0] > div.card-body.p-0[1] > div.single-line-info.border-light[2] > div.border-light.border-0[1] > div.d-lg-flex.gap-2.mt-3[0]` — Children count: ref=3, local=1
13. `body > body[0] > main[0] > div#main-container[0] > div.row.justify-content-center[1] > div.col-lg-8.offset-lg-1[1] > div.tab-content[1] > div#data-ex-disservizio2[1] > div.row[0] > div.cmp-card.mb-4.mb-lg-30[1] > div.card.has-bkg-grey.shadow-sm[0] > div.card-body.p-0[0] > div.cmp-info-button-card[0] > div.card.p-3.p-lg-4[0] > div.card-body.p-0[0] > p.card-info[0]` — Children count: ref=0, local=1
14. `body > body[0] > main[0] > div#main-container[0] > div.row.justify-content-center[1] > div.col-lg-8.offset-lg-1[1] > div.tab-content[1] > div#data-ex-disservizio2[1] > div.row[0] > div.cmp-card.mb-4.mb-lg-30[1] > div.card.has-bkg-grey.shadow-sm[0] > div.card-body.p-0[0] > div.cmp-info-button-card[0] > div.card.p-3.p-lg-4[0] > div.card-body.p-0[0] > div.accordion-item[0] > div#collapse2[1] > div.accordion-body.p-0[0] > div.cmp-info-summary.bg-white.border-0[0] > div.card[0] > div.card-body.p-0[1] > div.single-line-info.border-light[2] > div.border-light.border-0[1] > div.d-lg-flex.gap-2.mt-3[0]` — Children count: ref=3, local=1
15. `body > body[0] > main[0] > div#main-container[0] > div.row.justify-content-center[1] > div.col-lg-8.offset-lg-1[1] > div.tab-content[1] > div#data-ex-disservizio2[1] > div.row[0] > div.cmp-card.mb-4.mb-lg-30[2] > div.card.has-bkg-grey.shadow-sm[0] > div.card-body.p-0[0] > div.cmp-info-button-card[0] > div.card.p-3.p-lg-4[0] > div.card-body.p-0[0] > p.card-info[0]` — Children count: ref=0, local=1
16. `body > body[0] > main[0] > div#main-container[0] > div.row.justify-content-center[1] > div.col-lg-8.offset-lg-1[1] > div.tab-content[1] > div#data-ex-disservizio2[1] > div.row[0] > div.cmp-card.mb-4.mb-lg-30[2] > div.card.has-bkg-grey.shadow-sm[0] > div.card-body.p-0[0] > div.cmp-info-button-card[0] > div.card.p-3.p-lg-4[0] > div.card-body.p-0[0] > div.accordion-item[0] > div#collapse3[1] > div.accordion-body.p-0[0] > div.cmp-info-summary.bg-white.border-0[0] > div.card[0] > div.card-body.p-0[1] > div.single-line-info.border-light[2] > div.border-light.border-0[1] > div.d-lg-flex.gap-2.mt-3[0]` — Children count: ref=3, local=1

---

## 🟡 WARN — Attribute/Class Differences

1. `body > body[0] > header.it-header-wrapper[0] > div.it-header-slim-wrapper[0] > div.container[0] > div.row[0] > div.col-12[0] > div.it-header-slim-wrapper-content[0] > div.it-header-slim-right-zone[0] > a.btn.btn-primary.btn-icon[0]` — missing classes: btn-full, btn-primary; extra classes: btn-outline-light
2. `body > body[0] > header.it-header-wrapper[0] > div.it-nav-wrapper[1] > div.it-header-center-wrapper[0] > div.container[0] > div.row[0] > div.col-12[0] > div.it-header-center-content-wrapper[0] > div.it-right-zone[1]` — missing classes: it-right-zone; extra classes: it-brand-wrapper
3. `body > body[0] > main[0] > div#main-container[0] > div.row.justify-content-center[1] > div.col-lg-8.offset-lg-1[1] > div.tab-content[1] > div#data-ex-disservizio2[1] > div.row[0] > div.cmp-card.mb-4.mb-lg-30[1] > div.card.has-bkg-grey.shadow-sm[0] > div.card-body.p-0[0] > div.cmp-info-button-card[0] > div.card.p-3.p-lg-4[0] > div.card-body.p-0[0] > div.accordion-item[0] > div#collapse2[1]` — extra classes: pb-0
4. `body > body[0] > main[0] > div#main-container[0] > div.row.justify-content-center[1] > div.col-lg-8.offset-lg-1[1] > div.tab-content[1] > div#data-ex-disservizio2[1] > div.row[0] > div.cmp-card.mb-4.mb-lg-30[2] > div.card.has-bkg-grey.shadow-sm[0] > div.card-body.p-0[0] > div.cmp-info-button-card[0] > div.card.p-3.p-lg-4[0] > div.card-body.p-0[0] > div.accordion-item[0] > div#collapse3[1]` — extra classes: pb-0

---

## 🎯 Action Items

✅ **Target reached!** Proceed to CSS/JS visual parity.

---

*Generated by `compare-html-body.py` on 2026-04-08 09:32*
