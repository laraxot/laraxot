# Block 05: Feedback Widget (Rating Pagina)

**ID:** `feedback-rating`  
**Reference:** Homepage - Widget feedback utente con rating stelle + survey multi-step  
**Bootstrap Italia Classi:** `cmp-rating`, `rating-star`, `cmp-steps-rating`, `form-rating`  
**Tailwind Equivalente:** Da implementare con `@apply` + Alpine.js per interazioni

## 📍 Posizione nella Pagina

- **Dopo:** Ricerca e Link Utili
- **Prima:** Footer
- **Sezione padre:** `<main id="content>` (fine)
- **Background:** Primary color (blu)

## 🏗️ Struttura HTML

```html
<div class="bg-primary">
  <div class="container">
    <div class="row d-flex justify-content-center bg-primary">
      <div class="col-12 col-lg-6">
        
        <div class="cmp-rating pt-lg-80 pb-lg-80" id="rating">
          <div class="card shadow card-wrapper" data-element="feedback">
            
            <!-- === STEP 0: Rating Stelle === -->
            <div class="cmp-rating__card-first">
              <div class="card-header border-0">
                <h2 class="title-medium-2-semi-bold mb-0" data-element="feedback-title">
                  Quanto sono chiare le informazioni su questa pagina?
                </h2>
              </div>
              
              <div class="card-body">
                <fieldset class="rating">
                  <legend class="visually-hidden">Valuta da 1 a 5 stelle la pagina</legend>
                  
                  <!-- 5 stelle (ordine inverso: 5→1) -->
                  <input type="radio" id="star5a" name="ratingA" value="5">
                  <label class="full rating-star active" for="star5a" data-element="feedback-rate-5">
                    <svg class="icon icon-sm" role="img" aria-labelledby="first-star">
                      <path d="M12 1.7L9.5 9.2H1.6L8 13.9l-2.4 7.6 6.4-4.7 6.4 4.7-2.4-7.6 6.4-4.7h-7.9L12 1.7z"/>
                    </svg>
                    <span class="visually-hidden" id="first-star">Valuta 5 stelle su 5</span>
                  </label>
                  
                  <input type="radio" id="star4a" name="ratingA" value="4">
                  <label class="full rating-star active" for="star4a" data-element="feedback-rate-4">
                    <!-- ... stella 4 ... -->
                  </label>
                  
                  <!-- ... stelle 3, 2, 1 ... -->
                  
                </fieldset>
              </div>
            </div>
            
            <!-- === STEP 1: Domande Approfondimento === -->
            <div class="form-rating d-none" data-step="1">
              <div class="cmp-steps-rating">
                
                <!-- Domande positive (se rating >= 4) -->
                <fieldset class="fieldset-rating-one d-none" data-element="feedback-rating-positive">
                  <legend class="iscrizioni-header w-100">
                    <h3 class="step-title d-flex flex-column flex-lg-row align-items-lg-center justify-content-between drop-shadow">
                      <span class="d-block text-wrap" data-element="feedback-rating-question">
                        Quali sono stati gli aspetti che hai preferito?
                      </span>
                      <span class="step">1/2</span>
                    </h3>
                  </legend>
                  
                  <div class="cmp-steps-rating__body">
                    <div class="cmp-radio-list">
                      <div class="card card-teaser shadow-rating">
                        <div class="card-body">
                          <div class="form-check m-0">
                            <div class="radio-body border-bottom border-light cmp-radio-list__item">
                              <input name="rating1" type="radio" id="radio-1">
                              <label for="radio-1" class="active" data-element="feedback-rating-answer">
                                Le indicazioni erano chiare
                              </label>
                            </div>
                            
                            <div class="radio-body border-bottom border-light cmp-radio-list__item">
                              <input name="rating1" type="radio" id="radio-2">
                              <label for="radio-2" class="active" data-element="feedback-rating-answer">
                                Le indicazioni erano complete
                              </label>
                            </div>
                            
                            <!-- ... altre opzioni ... -->
                            
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </fieldset>
                
                <!-- Domande negative (se rating <= 3) -->
                <fieldset class="fieldset-rating-two d-none" data-element="feedback-rating-negative">
                  <legend class="iscrizioni-header w-100">
                    <h3 class="step-title">
                      <span data-element="feedback-rating-question-negative">
                        Quali aspetti potrebbero essere migliorati?
                      </span>
                      <span class="step">1/2</span>
                    </h3>
                  </legend>
                  <!-- ... checkbox miglioramenti ... -->
                </fieldset>
                
              </div>
            </div>
            
            <!-- === STEP 2: Commenti Aggiuntivi === -->
            <div class="form-rating d-none" data-step="2">
              <!-- Textarea per commenti liberi -->
              <!-- Bottone: Indietro / Avanti -->
            </div>
            
            <!-- === STEP 3: Ringraziamento === -->
            <div class="cmp-rating__card-second d-none" data-step="3">
              <div class="card-header border-0 mb-0">
                <h2 class="title-medium-2-bold mb-0" id="rating-feedback">
                  Grazie, il tuo parere ci aiuterà a migliorare il servizio!
                </h2>
              </div>
            </div>
            
          </div>
        </div>
        
      </div>
    </div>
  </div>
</div>
```

## 🎯 Funzionalità

### Step 0: Rating Stelle
- Domanda: "Quanto sono chiare le informazioni su questa pagina?"
- 5 stelle cliccabili (SVG)
- Selezione immediata → mostra Step 1

### Step 1: Approfondimento
- **Rating alto (4-5 stelle):** Domande positive
  - "Quali sono stati gli aspetti che hai preferito?"
  - Radio buttons: chiarezza, completezza, ecc.
- **Rating basso (1-3 stelle):** Domande negative
  - "Quali aspetti potrebbero essere migliorati?"
  - Checkbox: navigazione, contenuti, design, ecc.

### Step 2: Commenti
- Textarea per feedback libero
- Max 200 caratteri (opzionale)
- Bottoni: Indietro / Invia

### Step 3: Ringraziamento
- Messaggio: "Grazie, il tuo parere ci aiuterà a migliorare il servizio!"
- Nessun bottone, feedback completato

## 📊 Dati JSON (Struttura Attesa)

```json
{
  "type": "feedback-rating",
  "rating": {
    "question": "Quanto sono chiare le informazioni su questa pagina?",
    "maxStars": 5,
    "endpoint": "/api/feedback/rating"
  },
  "followUp": {
    "positive": {
      "question": "Quali sono stati gli aspetti che hai preferito?",
      "options": [
        "Le indicazioni erano chiare",
        "Le indicazioni erano complete",
        "La navigazione era intuitiva",
        "Il design era gradevole"
      ]
    },
    "negative": {
      "question": "Quali aspetti potrebbero essere migliorati?",
      "options": [
        "La chiarezza dei contenuti",
        "La navigazione nel sito",
        "Il design della pagina",
        "La completezza delle informazioni"
      ]
    }
  },
  "comment": {
    "question": "Vuoi aggiungere altri dettagli? (opzionale, max 200 caratteri)",
    "maxLength": 200
  },
  "thankYou": "Grazie, il tuo parere ci aiuterà a migliorare il servizio!"
}
```

## 🎨 Design System Mapping

| Bootstrap | Tailwind (@apply) | Note |
|-----------|------------------|------|
| `cmp-rating` | `.cmp-feedback` | Container widget |
| `rating-star` | `.cmp-feedback__star` | Singola stella |
| `cmp-steps-rating` | `.cmp-feedback__steps` | Steps container |
| `fieldset-rating-one` | `.cmp-feedback__step--positive` | Step positivo |
| `cmp-radio-list` | `.cmp-radio-list` | Lista radio buttons |
| `shadow-rating` | `.cmp-feedback__card` | Card con ombra |
| `bg-primary` | `bg-primary` | Tailwind utility |

## 🔄 Alpine.js Implementation

```javascript
document.addEventListener('alpine:init', () => {
  Alpine.data('feedbackRating', () => ({
    currentStep: 0,
    rating: 0,
    followUpAnswers: [],
    comment: '',
    
    selectRating(value) {
      this.rating = value;
      this.currentStep = 1;
      
      // Determina se positivo o negativo
      this.isPositive = value >= 4;
    },
    
    async submitFeedback() {
      await fetch('/api/feedback', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          rating: this.rating,
          followUp: this.followUpAnswers,
          comment: this.comment,
          page: window.location.pathname
        })
      });
      
      this.currentStep = 3; // Ringraziamento
    }
  }));
});
```

## ♿ Accessibilità

- ✅ `fieldset` + `legend` per gruppo rating
- ✅ `visually-hidden` per label stelle
- ✅ `role="img"` + `aria-labelledby` su SVG
- ✅ Radio buttons con label associate
- ⚠️ Step navigation: `aria-live="polite"` per cambiamenti
- ⚠️ Textarea: `aria-describedby` per maxLength

## 📐 Responsive Behavior

| Breakpoint | Layout |
|------------|--------|
| Mobile | Widget full width, padding ridotto |
| Desktop | Widget centrato, `col-lg-6` (50% width) |

## 🔗 Blocchi Correlati

- [Block 04: Ricerca e Link Utili](./block-04-search-useful-links.md)
- [Block 06: Footer](./block-06-footer.md)

## 📝 Note Implementazione

- **Alpine.js:** Gestire step transition
- **Backend:** Endpoint `/api/feedback` per salvataggio
- **Analytics:** Track feedback per report AGID
- **Privacy:** NO dati personali, solo rating + commenti
- **Cache:** Widget non cacheable (dinamico)
