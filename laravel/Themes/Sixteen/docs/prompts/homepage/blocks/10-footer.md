# Block 10: Footer

> Footer con link utili, contatti, Europa

---

## Reference
**URL**: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html  
**Selettore**: `footer.it-footer`  

---

## Struttura HTML

```html
<footer class="it-footer" id="footer">
  <div class="footer-top">
    <div class="container">
      <div class="row">
        <!-- Colonna: Il Comune -->
        <div class="col-lg-3 col-md-3 pb-2">
          <h4 class="footer-heading">Il Comune</h4>
          <ul class="footer-list">
            <li><a href="#">Organi di governo</a></li>
            <li><a href="#">Aree amministrative</a></li>
            <li><a href="#">Enti e fondazioni</a></li>
            <li><a href="#">Politici</a></li>
            <li><a href="#">Personale amministrativo</a></li>
            <li><a href="#">Documenti e dati</a></li>
          </ul>
        </div>
        
        <!-- Colonna: Servizi -->
        <div class="col-lg-3 col-md-3 pb-2">
          <h4 class="footer-heading">Servizi</h4>
          <ul class="footer-list">
            <li><a href="#">Servizi per la persona</a></li>
            <li><a href="#">Istruzione</a></li>
            <li><a href="#">Servizi digitali</a></li>
            <!-- Altri... -->
          </ul>
        </div>
        
        <!-- Colonna: Novità -->
        <!-- Colonna: Assistenza -->
      </div>
    </div>
  </div>
  
  <!-- Footer Middle: Contatti -->
  <div class="footer-middle">
    <div class="container">
      <div class="row">
        <div class="col-lg-3 col-md-3 pb-2">
          <h4 class="footer-heading">Contatti</h4>
          <p>Comune di...</p>
          <p>Via...</p>
          <p>Tel...</p>
        </div>
        <!-- Social, Logo, Link utili... -->
      </div>
    </div>
  </div>
  
  <!-- Footer Bottom: Europa + Credits -->
  <div class="footer-bottom">
    <div class="container">
      <div class="row">
        <div class="col-lg-3 col-md-3 pb-2">
          <div class="footer-social">
            <a href="#" target="_blank">
              <svg class="icon icon-sm icon-white"><use href="#it-twitter"></use></svg>
            </a>
            <!-- Altri social... -->
          </div>
        </div>
        
        <!-- Logo Europa -->
        <div class="col-lg-3 col-md-3 pb-2">
          <a href="#" target="_blank">
            <img src="logo-eu-inverted.svg" alt="Unione Europea">
          </a>
        </div>
        
        <!-- Privacy, Cookies, Credits -->
        <div class="col-lg-3 col-md-3 pb-2">
          <a href="#">Privacy policy</a>
          <a href="#">Cookie policy</a>
          <a href="#">Note legali</a>
          <a href="#">Dichiarazione accessibilità</a>
        </div>
      </div>
    </div>
  </div>
</footer>
```

---

## Elementi Chiave

| Elemento | Classe | Scopo |
|----------|--------|-------|
| Footer | `.it-footer#footer` | Container principale |
| Top | `.footer-top` | Link per categoria |
| Middle | `.footer-middle` | Contatti + logo |
| Bottom | `.footer-bottom` | Social + Europa + legal |
| Heading | `.footer-heading` | Titolo colonna |
| List | `.footer-list` | Lista link |
| Social | `.footer-social` | Icone social |
| Logo EU | `logo-eu-inverted.svg` | Logo Unione Europea |

---

## Local Implementation

**Blade**: `Themes/Sixteen/resources/views/layouts/app.blade.php` (footer incluso)

---

## 🔗 Link Bidirezionali

- ← [Blocks Index](./00-index.md)
- → [Contacts Section](./09-contacts-section.md)

---

**Stato**: ✅ Documentato
