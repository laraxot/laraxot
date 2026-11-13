/**
 * Componenti Alpine del blocco homepage Design Comuni (resources/views/components/blocks/homepage.blade.php).
 * Riproducono i comportamenti osservati nella pagina di riferimento senza il suo bundle.
 * Verifica: scripts/homepage-parity-defects.sh (clic + confronto pixel).
 */
import Splide from '@splidejs/splide';
import { initModalFocus } from './modal-focus.js';

/**
 * Opzioni dei caroselli, misurate dal riferimento (le sceglie la classe dell'elemento, come fa il bundle del riferimento):
 * `it-calendar-wrapper` (4 colonne, calendario), `it-carousel-landscape-abstract-three-cols` (3 colonne, galleria) e `carousel-4-card` (4 card, opzioni di scripts.js del riferimento).
 */
const CAROUSELS = {
    'it-calendar-wrapper': {
        type: 'slide',
        perPage: 4,
        gap: 0,
        padding: { left: 0, right: 0 },
        arrows: false,
        slideFocus: true,
        breakpoints: {
            560: { perPage: 1, padding: { left: 24, right: 24 } },
            768: { perPage: 2 },
            992: { perPage: 3 },
        },
    },
    'carousel-4-card': {
        pagination: false,
        arrows: false,
        gap: '1rem',
        perPage: 4,
        perMove: 1,
        speed: 800,
        breakpoints: {
            1200: { perPage: 3, pagination: true },
            992: { perPage: 2, gap: '1rem' },
            768: { perPage: 1, gap: 0 },
        },
    },
    'it-carousel-landscape-abstract-three-cols': {
        type: 'slide',
        perPage: 3,
        gap: 24,
        padding: { left: 0, right: 0 },
        arrows: false,
        slideFocus: true,
        breakpoints: {
            768: { perPage: 1, gap: 24, padding: { left: 0, right: 0 } },
            992: { perPage: 2, gap: 24, padding: { left: 40, right: 40 } },
        },
    },
};

export const dcCarousel = () => ({
    init() {
        const key = Object.keys(CAROUSELS).find((name) => this.$el.classList.contains(name));
        if (key) new Splide(this.$el, CAROUSELS[key]).mount();
    },
});

/**
 * Valutazione a passi: il voto (>3 stelle: domande positive, altrimenti negative) apre il passo 1;
 * "Avanti" scorre i passi, "Indietro" torna indietro; finito l'ultimo passo il form si nasconde.
 */
export const dcRating = () => ({
    step: 0,
    rating: 0,
    hover: 0,
    feedbackType: '',
    selectedOption: '',
    textFeedback: '',

    init() {
        this.$el.querySelectorAll('.rating input').forEach((input) => input.addEventListener('change', () => {
            this.deactivate();
            this.hide(this.form());
            this.fieldsets().forEach((fieldset) => this.hide(fieldset));
            this.step = 0;
            if (input.checked) this.next();
        }));
        this.$el.querySelector('.btn-next')?.addEventListener('click', () => { this.deactivate(); this.next(); });
        this.$el.querySelector('.btn-back')?.addEventListener('click', () => { this.deactivate(); this.back(); });
    },

    form: () => document.querySelector('.cmp-rating .form-rating'),
    first() { return this.$el.querySelector('.cmp-rating__card-first'); },
    fieldsets() { return [...this.$el.querySelectorAll('.fieldset-rating-one, .fieldset-rating-two')]; },
    stepCount() { return this.$el.querySelectorAll('[data-step]').length; },
    stepEl(n) { return this.$el.querySelector(`[data-step="${n}"]`); },
    hide: (el) => el.classList.add('d-none'),
    show: (el) => el.classList.remove('d-none'),

    deactivate() {
        const current = this.$el.querySelector('[data-step].active');
        if (current) { this.hide(current); current.classList.remove('active'); }
    },

    next() {
        this.show(this.form());
        const positive = Number(this.$el.querySelector('[name="ratingA"]:checked').value) > 3;
        this.show(this.$el.querySelector(positive ? '.fieldset-rating-one' : '.fieldset-rating-two'));
        const target = this.stepEl(this.step + 1);
        this.show(target);
        target.classList.add('active');
        this.step += 1;
        if (this.step === this.stepCount()) {
            this.hide(this.form());
            this.hide(this.first());
            document.dispatchEvent(new Event('feedback-submit'));
        }
    },

    back() {
        this.show(this.form());
        this.step -= 1;
        if (this.step === 0) {
            this.hide(this.form());
            this.show(this.first());
            this.fieldsets().forEach((fieldset) => this.hide(fieldset));
            this.$el.querySelector('[name="ratingA"]:checked').checked = false;
            return;
        }
        const current = this.stepEl(this.step + 1);
        this.hide(current);
        current.classList.remove('active');
        const previous = this.stepEl(this.step);
        this.show(previous);
        previous.classList.add('active');
    },
});

/**
 * Navigazione laterale con scroll-spy e barra di avanzamento (pagine a sezioni `.it-page-section`): il link della sezione
 * raggiunta diventa `active` e la barra mostra quanto del contenitore delle sezioni e' stato scorso. Comportamento misurato
 * dal riferimento (padding di scroll 10 px, barra a 0% finche' il contenitore non raggiunge la cima).
 */
const NAVSCROLL_PADDING = 10;

export const dcNavscroll = () => ({
    init() {
        const update = () => this.update();
        window.addEventListener('scroll', update, { passive: true });
        window.addEventListener('resize', update);
        window.addEventListener('load', () => setTimeout(update, 150));
        update();
    },

    update() {
        const container = document.querySelector('.it-page-sections-container');
        if (!container) return;
        const links = [...this.$el.querySelectorAll('a.nav-link')];
        const sections = [...document.querySelectorAll('.it-page-section')];
        const scrolled = document.scrollingElement.scrollTop - container.offsetTop;

        sections.forEach((section, index) => {
            if (section.offsetTop - container.offsetTop <= scrolled + NAVSCROLL_PADDING && index < links.length) {
                this.$el.querySelectorAll('a.nav-link.active').forEach((link) => link.classList.remove('active'));
                links[index].classList.add('active');
            }
        });

        const rect = container.getBoundingClientRect();
        // contenitore senza altezza (passo del wizard ancora nascosto all'avvio): 0/0 dava NaN in aria-valuenow (axe: aria-valid-attr-value, DCP-128)
        const progress = rect.top <= 0 && rect.height > 0 ? Math.min(100, Math.max(0, (Math.abs(rect.top) / rect.height) * 100)) : 0;
        document.querySelectorAll('.it-navscroll-progressbar').forEach((bar) => {
            bar.style.width = `${progress}%`;
            bar.setAttribute('aria-valuenow', String(progress));
        });
    },
});

/**
 * Anello di focus: il riferimento lo nasconde quando l'elemento e' attivato con il mouse (classe `focus--mouse` e
 * `data-focus-mouse`) e lo lascia per la tastiera. Un solo elemento alla volta; Tab o altri tasti di navigazione lo tolgono.
 */
const FOCUS_TARGETS = 'a, button, input, select, textarea, summary, [tabindex]';
let mouseFocused = null;

const clearMouseFocus = () => {
    if (!mouseFocused) return;
    mouseFocused.classList.remove('focus--mouse');
    mouseFocused.setAttribute('data-focus-mouse', 'false');
    mouseFocused = null;
};

export const initFocusMouse = () => {
    document.addEventListener('mousedown', (event) => {
        const hit = event.target.closest?.(FOCUS_TARGETS) ?? event.target.closest?.('label')?.control;
        const target = hit?.tagName === 'LABEL' ? hit.control : hit;
        clearMouseFocus();
        if (!target) return;
        target.classList.add('focus--mouse');
        target.setAttribute('data-focus-mouse', 'true');
        mouseFocused = target;
    });
    document.addEventListener('keydown', (event) => event.key === 'Tab' && clearMouseFocus());
};

/**
 * Comportamenti di `scripts.js` del riferimento per i passi di una procedura (pagine /tests):
 * - `.saveBtn`: mostra per 3 s `#alert-message` (toglie `d-none`) e aggiunge `pb-4` a `.steppers-nav`;
 * - `.radio-input` / `.radio-card`: alla scelta, la scheda corrispondente riceve `has-border-green`, le altre la perdono.
 */
const SAVE_MESSAGE_MS = 3000;

export const initProcedureSteps = () => {
    const alert = document.getElementById('alert-message');
    const nav = document.querySelector('.steppers-nav');
    document.querySelectorAll('.saveBtn').forEach((button) => button.addEventListener('click', () => {
        alert?.classList.remove('d-none');
        nav?.classList.add('pb-4');
        setTimeout(() => {
            alert?.classList.add('d-none');
            nav?.classList.remove('pb-4');
        }, SAVE_MESSAGE_MS);
    }));

    const cards = [...document.querySelectorAll('.radio-card')];
    document.querySelectorAll('.radio-input').forEach((input, index) => input.addEventListener('change', () => {
        cards.forEach((card, position) => card.classList.toggle('has-border-green', position === index));
    }));
};

/**
 * Campi dei moduli IMU/permessi (script `scripts.js` del riferimento):
 * - `#toggle-toggle` su change: `.infoWrapper` alterna `d-none`.
 * - `.check-input` su change: la `.cmp-info-checkbox` con lo stesso indice alterna `has-border-green`.
 * - `input[type="text"]` su input: con testo inserisce dopo il campo `button.clean-input` (clic: svuota il campo, rimuove il pulsante e toglie
 *   `active` dall'etichetta `.cmp-input__label`), senza testo lo rimuove. Lo sprite dell'icona si ricava dalle `<use>` gia' nella pagina.
 * ponytail: nel riferimento un campo vuoto senza pulsante lancia un errore; qui `?.` lo ignora.
 */
const CLEAN_INPUT_LABEL = 'Elimina testo di ricerca';

const spriteOf = (icon) => {
    const href = document.querySelector('use[href*="sprites.svg#"]')?.getAttribute('href');
    return href ? `${href.split('#')[0]}#${icon}` : `#${icon}`;
};

const syncCleanInput = (field) => {
    const holder = field.parentElement;
    const existing = holder.querySelector('.clean-input');
    if (!field.value.length) return existing?.remove();
    if (existing) return;
    field.insertAdjacentHTML('afterend', `<button type="button" class="clean-input" aria-label="${CLEAN_INPUT_LABEL}"><svg class="icon"><use href="${spriteOf('it-close')}"></use></svg></button>`);
    const button = holder.querySelector('.clean-input');
    button.addEventListener('click', () => {
        field.value = '';
        button.remove();
        holder.querySelector('.cmp-input__label')?.classList.remove('active');
    });
};

export const initFormFields = () => {
    const toggle = document.querySelector('#toggle-toggle');
    const info = document.querySelector('.infoWrapper');
    toggle?.addEventListener('change', () => info?.classList.toggle('d-none'));

    const boxes = [...document.querySelectorAll('.cmp-info-checkbox')];
    document.querySelectorAll('.check-input').forEach((input, index) => input.addEventListener('change', () => boxes[index]?.classList.toggle('has-border-green')));

    document.querySelectorAll('input[type="text"]').forEach((field) => field.addEventListener('input', () => syncCleanInput(field)));
};

/**
 * Validazione dei form del riferimento (libreria JustValidate nel riferimento: `#justValidateForm`, `#justValidateMulta`).
 * Alla conferma ogni campo `[required]` vuoto (e le email non valide) riceve `is-invalid`, `aria-invalid`, `aria-describedby` e un'etichetta
 * `div.form-feedback.just-validate-error-label` in coda al contenitore del campo; dopo il primo tentativo ogni campo si ricontrolla mentre
 * si scrive. Se tutto e' valido l'invio nativo (che ricaricherebbe la pagina di prova) e' annullato e parte `dc-form-valid`.
 * ponytail: messaggi in italiano fissi, come nel riferimento; per altre lingue passarli da data-attributes quando servira'.
 */
const VALIDATION_MESSAGES = {
    required: 'Questo campo è richiesto',
    email: 'Email non valida',
    maxlength: (max) => `Questo campo può contenere un massimo di ${max} caratteri`,
};

export const dcFormValidate = () => ({
    attempted: false,

    init() {
        this.$el.setAttribute('novalidate', '');
        this.$el.addEventListener('submit', (event) => this.submit(event));
        this.$el.querySelectorAll('button.send, [type="submit"]').forEach((button) => button.addEventListener('click', (event) => {
            if (button.type === 'submit') return;
            event.preventDefault();
            this.submit(event);
        }));
        this.$el.addEventListener('input', (event) => this.attempted && this.check(event.target));
        this.$el.addEventListener('change', (event) => this.attempted && this.check(event.target));
    },

    message(field) {
        const value = field.type === 'checkbox' || field.type === 'radio' ? (field.checked ? 'x' : '') : field.value.trim();
        if (field.hasAttribute('required') && value === '') return VALIDATION_MESSAGES.required;
        if (field.type === 'email' && value !== '' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) return VALIDATION_MESSAGES.email;
        const max = Number(field.getAttribute('maxlength'));
        if (max > 0 && value.length > max) return VALIDATION_MESSAGES.maxlength(max);
        return '';
    },

    check(field) {
        // il voto (`.cmp-rating`) non e' un campo del modulo: JustValidate nel riferimento valida solo i campi dichiarati
        if (!field?.matches?.('input, select, textarea') || field.closest('.cmp-rating')) return true;
        const label = field.parentElement.querySelector(`.just-validate-error-label[id="${field.id}-error-0"]`);
        const error = this.message(field);
        label?.remove();
        field.classList.toggle('is-invalid', error !== '');
        if (error === '') {
            ['data-just-validate-fallback-disabled', 'aria-describedby', 'aria-invalid'].forEach((name) => field.removeAttribute(name));
            return true;
        }
        field.setAttribute('data-just-validate-fallback-disabled', 'false');
        field.setAttribute('aria-describedby', `${field.id}-error-0`);
        field.setAttribute('aria-invalid', 'true');
        const text = Object.assign(document.createElement('div'), { id: `${field.id}-error-0`, className: 'form-feedback just-validate-error-label', textContent: error });
        field.parentElement.appendChild(text);
        return false;
    },

    submit(event) {
        this.attempted = true;
        event.preventDefault();
        const valid = [...this.$el.querySelectorAll('input, select, textarea')].map((field) => this.check(field)).every(Boolean);
        if (valid) this.$el.dispatchEvent(new CustomEvent('dc-form-valid', { bubbles: true }));
    },
});

/**
 * Tab del riferimento (`[data-bs-toggle="tab"]`, Bootstrap Tab): al clic il link diventa `active show` con `aria-selected`, gli altri lo perdono,
 * e il pannello indicato da `href`/`data-bs-target` riceve `active show` mentre i fratelli nello stesso `.tab-content` lo perdono.
 * Salta le tab dei ticket (`#tabDisservizio`), gia' gestite da `initTicketTabs` in app.js.
 */
export const initTabs = () => {
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach((link) => {
        if (link.closest('#tabDisservizio')) return;
        link.addEventListener('click', (event) => {
            const selector = link.getAttribute('data-bs-target') || link.getAttribute('href');
            const pane = selector && selector !== '#' ? document.querySelector(selector) : null;
            if (!pane) return;
            event.preventDefault();
            const list = link.closest('.nav, [role="tablist"], ul') ?? link.parentElement;
            list.querySelectorAll('[data-bs-toggle="tab"]').forEach((other) => {
                other.classList.remove('active', 'show');
                other.setAttribute('aria-selected', 'false');
            });
            link.classList.add('active', 'show');
            link.setAttribute('aria-selected', 'true');
            pane.parentElement.querySelectorAll(':scope > .tab-pane').forEach((sibling) => sibling.classList.remove('active', 'show'));
            pane.classList.add('active', 'show');
        });
    });
};

/**
 * Montaggio per selettore: il markup resta identico al riferimento (nessun x-data nell'HTML) e Alpine inizializza
 * i componenti sugli elementi che hanno quegli hook. Chiamata da app.js su `alpine:init`, prima che Alpine scansioni il DOM.
 */
export const mountDesignComuniComponents = () => {
    // DCP-288 (FO-1-07): focus, trap e ritorno del focus delle modali (ricerca dell'intestazione); vedi theme/modal-focus.js
    initModalFocus();
    if (document.querySelector('.page-content[data-dc]')) {
        initFocusMouse();
        initProcedureSteps();
        initFormFields();
        initTabs();
    }
    document.querySelectorAll('[data-bs-carousel-splide], .carousel-4-card').forEach((el) => el.setAttribute('x-data', 'dcCarousel'));
    document.querySelectorAll('.cmp-rating#rating').forEach((el) => el.setAttribute('x-data', 'dcRating'));
    document.querySelectorAll('[data-bs-navscroll]').forEach((el) => el.setAttribute('x-data', 'dcNavscroll'));
    document.querySelectorAll('#justValidateForm, #justValidateMulta').forEach((el) => el.setAttribute('x-data', 'dcFormValidate'));
};
