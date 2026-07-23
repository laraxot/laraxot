# Block 06: Footer Principale

**ID:** `footer-main`  
**Reference:** Homepage - Footer completo con link navigazione, contatti, legal e social  
**Bootstrap Italia Classi:** `it-footer`, `it-footer-main`, `footer-list`, `footer-heading-title`  
**Tailwind Equivalente:** Da implementare con `@apply`

## 📍 Posizione nella Pagina

- **Dopo:** Tutti i blocchi主要内容 (fine `<main>`)
- **Elemento:** `<footer id="footer">`
- **Sezione padre:** Root `<body>` (ultimo elemento)

## 🏗️ Struttura HTML

```html
<footer class="it-footer" id="footer">
  
  <!-- === SEZIONE 1: Brand + Logo UE === -->
  <div class="it-footer-main">
    <div class="container">
      <div class="row">
        <div class="col-12 footer-items-wrapper logo-wrapper">
          
          <!-- Logo Unione Europea -->
          <img class="ue-logo" 
               src="../assets/images/logo-eu-inverted.svg" 
               alt="logo Unione Europea">
          
          <!-- Brand Comune -->
          <div class="it-brand-wrapper">
            <a href="#">
              <svg class="icon" aria-hidden="true">
                <use xlink:href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-pa"></use>
              </svg>
              <div class="it-brand-text">
                <h2 class="no_toc">Nome del Comune</h2>
              </div>
            </a>
          </div>
          
        </div>
      </div>
      
      <!-- === SEZIONE 2: Colonne Link === -->
      <div class="row">
        
        <!-- Colonna 1: Amministrazione -->
        <div class="col-md-3 footer-items-wrapper">
          <h4 class="footer-heading-title">Amministrazione</h4>
          <ul class="footer-list">
            <li><a href="#">Organi di governo</a></li>
            <li><a href="#">Aree amministrative</a></li>
            <li><a href="#">Uffici</a></li>
            <li><a href="#">Enti e fondazioni</a></li>
            <li><a href="#">Politici</a></li>
            <li><a href="#">Personale amministrativo</a></li>
            <li><a href="#">Documenti e dati</a></li>
          </ul>
        </div>
        
        <!-- Colonna 2-3: Categorie di servizio (span 2 colonne) -->
        <div class="col-md-6 footer-items-wrapper">
          <h4 class="footer-heading-title">Categorie di servizio</h4>
          <div class="row">
            <div class="col-md-6">
              <ul class="footer-list">
                <li><a href="#">Anagrafe e stato civile</a></li>
                <li><a href="#">Cultura e tempo libero</a></li>
                <li><a href="#">Vita lavorativa</a></li>
                <li><a href="#">Imprese e commercio</a></li>
                <li><a href="#">Appalti pubblici</a></li>
                <li><a href="#">Catasto e urbanistica</a></li>
                <li><a href="#">Turismo</a></li>
                <li><a href="#">Mobilità e trasporti</a></li>
              </ul>
            </div>
            <div class="col-md-6">
              <ul class="footer-list">
                <li><a href="#">Educazione e formazione</a></li>
                <li><a href="#">Giustizia e sicurezza pubblica</a></li>
                <li><a href="#">Tributi, finanze e contravvenzioni</a></li>
                <li><a href="#">Ambiente</a></li>
                <li><a href="#">Salute, benessere e assistenza</a></li>
                <!-- ... altri ... -->
              </ul>
            </div>
          </div>
        </div>
        
        <!-- Colonna 4: Novità + Vivere il Comune -->
        <div class="col-md-3 footer-items-wrapper">
          <h4 class="footer-heading-title">Novità</h4>
          <ul class="footer-list">
            <li><a href="#">Notizie</a></li>
            <li><a href="#">Comunicati stampa</a></li>
            <li><a href="#">Avvisi e bandi</a></li>
          </ul>
          
          <h4 class="footer-heading-title mt-4">Vivere il Comune</h4>
          <ul class="footer-list">
            <li><a href="#">Luoghi di interesse</a></li>
            <li><a href="#">Eventi</a></li>
          </ul>
        </div>
        
      </div>
      
      <!-- === SEZIONE 3: Contatti === -->
      <div class="row footer-contact-row">
        <div class="col-12 footer-items-wrapper">
          <div class="footer-contact-card">
            <h4 class="footer-heading-title">Contatti</h4>
            <address>
              <strong>Comune di Nome Comune</strong><br>
              Via Roma 123<br>
              00100 Città (PROV)<br>
              Cod. Fisc./P. IVA: 00000000000
            </address>
            
            <div class="footer-contact-links">
              <p>
                <strong>URP - Ufficio Relazioni con il Pubblico</strong><br>
                Numero verde: 800 000 000<br>
                SMS/WhatsApp: +39 000 000 0000<br>
                PEC: comune@pec.it<br>
                Centralino: +39 000 000 0000
              </p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- === SEZIONE 4: Link Secondari === -->
      <div class="row footer-secondary-row">
        <div class="col-12">
          <nav class="footer-secondary-links" aria-label="Link secondari">
            <a href="#">Leggi le FAQ</a>
            <span class="separator">|</span>
            <a href="#">Prenota appuntamento</a>
            <span class="separator">|</span>
            <a href="#">Segnala disservizio</a>
            <span class="separator">|</span>
            <a href="#">Richiesta assistenza</a>
          </nav>
        </div>
      </div>
      
      <!-- === SEZIONE 5: Link Legal === -->
      <div class="row footer-legal-row">
        <div class="col-12">
          <nav class="footer-legal-links" aria-label="Informazioni legali">
            <a href="#">Amministrazione trasparente</a>
            <span class="separator">|</span>
            <a href="#">Informativa privacy</a>
            <span class="separator">|</span>
            <a href="#">Note legali</a>
            <span class="separator">|</span>
            <a href="#">Dichiarazione accessibilità</a>
          </nav>
        </div>
      </div>
      
      <!-- === SEZIONE 6: Social === -->
      <div class="row footer-social-row">
        <div class="col-12">
          <div class="footer-social">
            <span>Seguici su</span>
            <ul>
              <li>
                <a href="#" target="_blank" rel="noopener noreferrer">
                  <svg class="icon icon-sm icon-white">
                    <use href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-twitter"></use>
                  </svg>
                  <span class="visually-hidden">Twitter</span>
                </a>
              </li>
              <li>
                <a href="#" target="_blank" rel="noopener noreferrer">
                  <svg class="icon icon-sm icon-white">
                    <use href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-facebook"></use>
                  </svg>
                  <span class="visually-hidden">Facebook</span>
                </a>
              </li>
              <!-- ... YouTube, Telegram, WhatsApp, RSS ... -->
            </ul>
          </div>
        </div>
      </div>
      
      <!-- === SEZIONE 7: Bottom Bar === -->
      <div class="row footer-bottom-row">
        <div class="col-12">
          <div class="footer-bottom">
            <a href="#">Media policy</a>
            <span class="separator">|</span>
            <a href="#">Mappa del sito</a>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</footer>
```

## 🎯 Funzionalità

### Struttura
- 7 sezioni verticali (brand, link, contatti, secondary, legal, social, bottom)
- Layout responsive: mobile (stack), desktop (4 colonne)
- Logo UE + Brand comune in alto

### Navigazione
- **Colonna 1:** Amministrazione (7 link)
- **Colonna 2-3:** Categorie di servizio (15+ link, 2 sotto-colonne)
- **Colonna 4:** Novità + Vivere il Comune (5 link)

### Contatti
- Indirizzo completo (card HTML `<address>`)
- Numeri URP (telefono, WhatsApp, PEC, centralino)

### Legal
- Amministrazione trasparente
- Privacy, Note legali, Accessibilità (AGID)

### Social
- 6 piattaforme: Twitter, Facebook, YouTube, Telegram, WhatsApp, RSS
- Icone bianche su sfondo scuro

## 📊 Dati JSON (Struttura Attesa)

```json
{
  "type": "footer-main",
  "brand": {
    "name": "Nome del Comune",
    "url": "/",
    "logoEU": "/assets/images/logo-eu-inverted.svg"
  },
  "navigation": {
    "columns": [
      {
        "title": "Amministrazione",
        "links": [
          { "label": "Organi di governo", "url": "/amministrazione/organi" },
          { "label": "Aree amministrative", "url": "/amministrazione/aree" }
        ]
      },
      {
        "title": "Categorie di servizio",
        "links": [
          { "label": "Anagrafe e stato civile", "url": "/servizi/anagrafe" },
          { "label": "Cultura e tempo libero", "url": "/servizi/cultura" }
        ]
      }
    ]
  },
  "contacts": {
    "name": "Comune di Nome Comune",
    "address": "Via Roma 123, 00100 Città (PROV)",
    "fiscalCode": "00000000000",
    "urp": {
      "tollFree": "800 000 000",
      "whatsapp": "+39 000 000 0000",
      "pec": "comune@pec.it",
      "phone": "+39 000 000 0000"
    }
  },
  "secondaryLinks": [
    { "label": "Leggi le FAQ", "url": "/faq" },
    { "label": "Prenota appuntamento", "url": "/prenota" },
    { "label": "Segnala disservizio", "url": "/segnala" },
    { "label": "Richiesta assistenza", "url": "/assistenza" }
  ],
  "legalLinks": [
    { "label": "Amministrazione trasparente", "url": "/trasparenza" },
    { "label": "Informativa privacy", "url": "/privacy" },
    { "label": "Note legali", "url": "/note-legali" },
    { "label": "Dichiarazione accessibilità", "url": "/accessibilita" }
  ],
  "social": [
    { "platform": "Twitter", "url": "https://twitter.com/comune", "icon": "it-twitter" },
    { "platform": "Facebook", "url": "https://facebook.com/comune", "icon": "it-facebook" }
  ],
  "bottomLinks": [
    { "label": "Media policy", "url": "/media-policy" },
    { "label": "Mappa del sito", "url": "/sitemap.xml" }
  ]
}
```

## 🎨 Design System Mapping

| Bootstrap | Tailwind (@apply) | Note |
|-----------|------------------|------|
| `it-footer` | `.cmp-footer` | Container principale |
| `it-footer-main` | `.cmp-footer__main` | Content wrapper |
| `footer-items-wrapper` | `.cmp-footer__col` | Colonna footer |
| `footer-heading-title` | `.cmp-footer__heading` | Titolo sezione |
| `footer-list` | `.cmp-footer__list` | Lista link |
| `logo-wrapper` | `.cmp-footer__brand` | Brand + logo UE |
| `ue-logo` | `.cmp-footer__eu-logo` | Logo UE styling |
| `footer-contact-card` | `.cmp-footer__contact` | Card contatti |
| `footer-secondary-links` | `.cmp-footer__secondary` | Link secondari |
| `footer-legal-links` | `.cmp-footer__legal` | Link legali |
| `footer-social` | `.cmp-footer__social` | Social icons |
| `footer-bottom` | `.cmp-footer__bottom` | Bottom bar |

## ♿ Accessibilità

- ✅ Logo UE con `alt` descrittivo
- ✅ Link social con `rel="noopener noreferrer"`
- ✅ Icone social con `visually-hidden` label
- ✅ Nav con `aria-label` per semantic regions
- ⚠️ Indirizzo `<address>`: verificare screen reader
- ⚠️ Separatori `|`: usare `aria-hidden="true"`

## 📐 Responsive Behavior

| Breakpoint | Layout |
|------------|--------|
| Mobile | Tutte le colonne stack verticali |
| Tablet | 2 colonne |
| Desktop | 4 colonne (3+6+3 md grid) |

## 🔗 Blocchi Correlati

- [Block 01: Hero/Contenuti in Evidenza](./block-01-hero-evidence.md)
- [Block 04: Ricerca e Link Utili](./block-04-search-useful-links.md)
- [Header Block (da documentare)](./block-00-header-nav.md)

## 📝 Note Implementazione

- **CMS Data:** Tutti i link configurabili da backend
- **Logo UE:** File SVG da assets tema
- **Social Icons:** SVG sprites da copiare in tema
- **Legal Links:** Required per compliance AGID
- **Performance:** Footer cacheable (statico)
