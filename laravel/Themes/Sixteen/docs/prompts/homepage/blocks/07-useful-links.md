# Block 07: Useful Links Section

> Ricerca rapida + link utili

---

## Reference
**URL**: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html  
**Selettore**: `section.useful-links-section`  

---

## Struttura HTML

```html
<section class="useful-links-section">
  <div class="container">
    <div class="row">
      <div class="col-12 col-lg-10">
        <h2>Come possiamo aiutarti?</h2>
        
        <!-- Search autocomplete -->
        <form role="search" action="/it/ricerca" method="get">
          <div class="cmp-input-search">
            <div class="form-group autocomplete-wrapper">
              <div class="input-group">
                <span class="input-group-text" id="mainSearch">
                  <svg class="icon icon-sm"><use href="#it-search"></use></svg>
                </span>
                <label for="search2" class="visually-hidden">Cerca nel sito</label>
                <input type="search" class="form-control" id="search2"
                       placeholder="Cerca una parola chiave" name="search"
                       data-bs-autocomplete="[]">
                <span class="input-group-append">
                  <button class="btn btn-primary" type="submit">
                    <span class="visually-hidden">Cerca</span>
                  </button>
                </span>
              </div>
            </div>
          </div>
        </form>
        
        <!-- Link utili -->
        <div class="link-list-wrapper mt-4">
          <ul class="link-list">
            <li>
              <a class="list-item mb-3 active ps-0" href="#">
                <span class="list-item-title-icon-wrapper"></span>
                <span class="text">Rilascio Carta Identità Elettronica (CIE)</span>
              </a>
            </li>
            <!-- Altri 5 link... -->
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>
```

---

## Elementi Chiave

| Elemento | Classe | Scopo |
|----------|--------|-------|
| Sezione | `.useful-links-section` | Container |
| Search | `.cmp-input-search` | Campo ricerca |
| Autocomplete | `.autocomplete-wrapper` | Wrapper con autocomplete |
| Input group | `.input-group` | Icona + input + bottone |
| Link list | `.link-list-wrapper` | Container link |
| Link item | `.list-item.mb-3.active.ps-0` | Singolo link utile |

---

## Local Implementation

**Blade**: `Themes/Sixteen/resources/views/components/blocks/search/support-links.blade.php`

---

## 🔗 Link Bidirezionali

- ← [Blocks Index](./00-index.md)
- → [Evidence Section](./06-evidence-section.md)
- → [Rating Feedback](./08-rating-feedback.md)

---

**Stato**: ✅ Documentato
