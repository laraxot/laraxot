# Block 08: Rating Feedback

> Valutazione stelle + feedback multi-step

---

## Reference
**URL**: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html  
**Selettore**: `.cmp-rating#rating`  

---

## Struttura HTML

```html
<div class="cmp-rating pt-lg-80 pb-lg-80" id="rating">
  <div class="card shadow card-wrapper" data-element="feedback">
    <!-- STEP 0: Star Rating -->
    <div class="cmp-rating__card-first">
      <div class="card-header border-0">
        <h2 class="title-medium-2-semi-bold mb-0" data-element="feedback-title">
          Quanto sono chiare le informazioni su questa pagina?
        </h2>
      </div>
      <div class="card-body">
        <fieldset class="rating" id="fieldset-rating-one">
          <legend class="visually-hidden">Valuta da 1 a 5 stelle la pagina</legend>
          
          <!-- 5 stelle (ordine inverso) -->
          <input type="radio" id="star5a" name="ratingA" value="5">
          <label class="full rating-star active" for="star5a" data-element="feedback-rate-5">
            <svg class="icon icon-sm" role="img" aria-labelledby="first-star">
              <path d="M12 1.7L9.5 9.2H1.6..."/>
            </svg>
            <span class="visually-hidden" id="first-star">Valuta 5 stelle su 5</span>
          </label>
          <!-- star4a, star3a, star2a, star1a... -->
        </fieldset>
      </div>
    </div>
    
    <!-- STEP 3: Thank You (hidden) -->
    <div class="cmp-rating__card-second d-none" data-step="3">
      <div class="card-header border-0 mb-0">
        <h2 class="title-medium-2-bold mb-0" id="rating-feedback">
          Grazie, il tuo parere ci aiuterà a migliorare il servizio!
        </h2>
      </div>
    </div>
    
    <!-- MULTI-STEP FORM (hidden) -->
    <form id="rating">
      <!-- STEP 1: Radio Feedback -->
      <div class="d-none" data-step="1">
        <div class="cmp-steps-rating">
          <!-- Positive -->
          <fieldset class="fieldset-rating-one d-none" data-element="feedback-rating-positive">
            <legend class="iscrizioni-header w-100">
              <h3 class="step-title">
                <span data-element="feedback-rating-question">
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
                      <!-- 4 more options... -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
          
          <!-- Negative -->
          <fieldset class="fieldset-rating-two d-none" data-element="feedback-rating-negative">
            <legend>
              <h3 class="step-title">
                <span>Dove hai incontrato le maggiori difficoltà?</span>
                <span class="step">1/2</span>
              </h3>
            </legend>
            <!-- 5 negative options... -->
          </fieldset>
        </div>
      </div>
      
      <!-- STEP 2: Text Input -->
      <div class="d-none" data-step="2">
        <div class="cmp-steps-rating">
          <fieldset>
            <legend>
              <h3 class="step-title">
                <span>Vuoi aggiungere altri dettagli?</span>
                <span class="step">2/2</span>
              </h3>
            </legend>
            <div class="cmp-steps-rating__body">
              <div class="form-group">
                <label for="formGroupExampleInputWithHelp">Dettaglio</label>
                <input type="text" class="form-control" id="formGroupExampleInputWithHelp"
                       maxlength="200" data-element="feedback-input-text">
                <small id="formGroupExampleInputWithHelpDescription" class="form-text">
                  Inserire massimo 200 caratteri
                </small>
              </div>
            </div>
          </fieldset>
        </div>
      </div>
      
      <!-- Navigation Buttons -->
      <div class="d-flex flex-nowrap pt-4 w-100 justify-content-center button-shadow">
        <button class="btn btn-outline-primary fw-bold me-4 btn-back" type="button">Indietro</button>
        <button class="btn btn-primary fw-bold btn-next" type="submit" form="rating">Avanti</button>
      </div>
    </form>
  </div>
</div>
```

---

## Elementi Chiave

| Elemento | Classe/ID | data-element | Scopo |
|----------|-----------|--------------|-------|
| Rating wrapper | `.cmp-rating#rating` | - | Container valutazione |
| Card | `.card-wrapper` | `feedback` | Card principale |
| Title | `.title-medium-2-semi-bold` | `feedback-title` | Domanda |
| Fieldset stars | `#fieldset-rating-one` | - | Gruppo stelle |
| Star label | `.full.rating-star.active` | `feedback-rate-N` | Label stella |
| Thank you | `.cmp-rating__card-second.d-none` | - | Messaggio grazie |
| Positive fieldset | `.fieldset-rating-one` | `feedback-rating-positive` | Feedback positivo |
| Negative fieldset | `.fieldset-rating-two` | `feedback-rating-negative` | Feedback negativo |
| Radio item | `.cmp-radio-list__item` | `feedback-rating-answer` | Opzione radio |
| Text input | `#formGroupExampleInputWithHelp` | `feedback-input-text` | Input dettaglio |
| Back button | `.btn-back` | - | Torna indietro |
| Next button | `.btn-next` | - | Vai avanti |

---

## Multi-Step Flow

```
Step 0: Click stelle (1-5)
  ↓
Step 1: Radio feedback (positivo se >=3, negativo se <3)
  ↓
Step 2: Input testo opzionale
  ↓
Step 3: Messaggio grazie
```

---

## JSON Structure

```json
{
  "type": "feedback-rating",
  "data": {
    "view": "pub_theme::components.blocks.feedback.rating",
    "title": {"it": "...", "en": "..."},
    "subtitle": {"it": "...", "en": "..."},
    "positive_question": {"it": "...", "en": "..."},
    "positive_options": {"it": ["...", ...], "en": ["...", ...]},
    "negative_question": {"it": "...", "en": "..."},
    "negative_options": {"it": ["...", ...], "en": ["...", ...]},
    "text_question": {"it": "...", "en": "..."},
    "text_label": {"it": "Dettaglio", "en": "Detail"},
    "text_help": {"it": "...", "en": "..."},
    "text_maxlength": 200,
    "btn_back": {"it": "Indietro", "en": "Back"},
    "btn_next": {"it": "Avanti", "en": "Next"},
    "star_legend": {"it": "...", "en": "..."},
    "star_labels": {"it": {"5": "...", ...}, "en": {...}}
  }
}
```

---

## Local Implementation

**Blade**: `Themes/Sixteen/resources/views/components/blocks/feedback/rating.blade.php`  
**Alpine.js**: `x-data`, `x-model`, `x-show`, `@click`, `x-show` per step management

---

## 🔗 Link Bidirezionali

- ← [Blocks Index](./00-index.md)
- → [Useful Links](./07-useful-links.md)
- → [Contacts Section](./09-contacts-section.md)

---

**Stato**: ✅ Documentato  
**Note**: Implementazione Alpine.js con struttura multi-step completa
