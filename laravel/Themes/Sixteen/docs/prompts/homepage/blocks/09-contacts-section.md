# Block 09: Contacts Section

> Contatti comune + segnalazione disservizi

---

## Reference
**URL**: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html  
**Selettore**: `.bg-grey-card.shadow-contacts`  

---

## Struttura HTML

```html
<div class="bg-grey-card shadow-contacts">
  <div class="container">
    <div class="row d-flex justify-content-center p-contacts">
      <div class="col-12 col-lg-6">
        <div class="cmp-contacts">
          <div class="card w-100">
            <div class="card-body">
              <!-- Contatti -->
              <h2 class="title-medium-2-semi-bold">Contatta il comune</h2>
              <ul class="contact-list p-0">
                <li>
                  <a class="list-item" href="#">
                    <svg class="icon icon-primary icon-sm">
                      <use href="#it-help-circle"></use>
                    </svg>
                    <span>Leggi le domande frequenti</span>
                  </a>
                </li>
                <li>
                  <a class="list-item" href="#" data-element="contacts">
                    <svg class="icon icon-primary icon-sm">
                      <use href="#it-mail"></use>
                    </svg>
                    <span>Richiedi assistenza</span>
                  </a>
                </li>
                <li>
                  <a class="list-item" href="#">
                    <svg class="icon icon-primary icon-sm">
                      <use href="#it-hearing"></use>
                    </svg>
                    <span>Chiama il numero verde 05 0505</span>
                  </a>
                </li>
                <li>
                  <a class="list-item" href="#" data-element="appointment-booking">
                    <svg class="icon icon-primary icon-sm">
                      <use href="#it-calendar"></use>
                    </svg>
                    <span>Prenota appuntamento</span>
                  </a>
                </li>
              </ul>
              
              <!-- Disservizi -->
              <h2 class="title-medium-2-semi-bold mt-4">Problemi in città</h2>
              <ul class="contact-list p-0">
                <li>
                  <a class="list-item" href="#">
                    <svg class="icon icon-primary icon-sm">
                      <use href="#it-map-marker-circle"></use>
                    </svg>
                    <span>Segnala disservizio</span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
```

---

## Elementi Chiave

| Elemento | Classe/ID | data-element | Icona | Scopo |
|----------|-----------|--------------|-------|-------|
| Container | `.bg-grey-card.shadow-contacts` | - | - | Sfondo grigio |
| Contacts wrapper | `.cmp-contacts` | - | - | Sezione contatti |
| Card | `.card.w-100` | - | - | Card contatti |
| Title 1 | `.title-medium-2-semi-bold` | - | - | "Contatta il comune" |
| Link FAQ | `.list-item` | - | `it-help-circle` | Domande frequenti |
| Link Email | `.list-item` | `contacts` | `it-mail` | Richiedi assistenza |
| Link Telefono | `.list-item` | - | `it-hearing` | Numero verde |
| Link Appuntamento | `.list-item` | `appointment-booking` | `it-calendar` | Prenota |
| Title 2 | `.title-medium-2-semi-bold.mt-4` | - | - | "Problemi in città" |
| Link Disservizio | `.list-item` | - | `it-map-marker-circle` | Segnala |

---

## JSON Structure

```json
{
  "type": "contacts-homepage",
  "data": {
    "view": "pub_theme::components.blocks.contact.homepage",
    "contact_links": [
      {"icon": "it-help-circle", "label": "Leggi le domande frequenti", "url": "#"},
      {"icon": "it-mail", "label": "Richiedi assistenza", "url": "#", "data_element": "contacts"},
      {"icon": "it-hearing", "label": "Chiama il numero verde 05 0505", "url": "#"},
      {"icon": "it-calendar", "label": "Prenota appuntamento", "url": "#", "data_element": "appointment-booking"}
    ],
    "report_links": [
      {"icon": "it-map-marker-circle", "label": "Segnala disservizio", "url": "#"}
    ]
  }
}
```

---

## Local Implementation

**Blade**: `Themes/Sixteen/resources/views/components/blocks/contact/homepage.blade.php`

---

## 🔗 Link Bidirezionali

- ← [Blocks Index](./00-index.md)
- → [Rating Feedback](./08-rating-feedback.md)
- → [Footer](./10-footer.md)

---

**Stato**: ✅ Documentato
