const SPRITE_PATH = '/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg';

function escapeHtml(value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function collectFaqItems(main) {
    return Array.from(main.querySelectorAll('.cmp-accordion .accordion-item'))
        .map((item, index) => {
            const button = item.querySelector('.accordion-button');
            const body = item.querySelector('.accordion-body');

            if (!button || !body) {
                return null;
            }

            return {
                id: `faq-item-${index + 1}`,
                question: button.textContent.trim(),
                answer: body.innerHTML.trim(),
            };
        })
        .filter(Boolean);
}

function renderFaqItems(items) {
    return items
        .map(
            (item) => `
                <div class="accordion-item" data-faq-question="${escapeHtml(item.question.toLowerCase())}">
                    <h2 class="accordion-header" id="heading-${item.id}">
                        <button
                            class="accordion-button"
                            type="button"
                            aria-expanded="false"
                            aria-controls="panel-${item.id}"
                            data-faq-toggle
                        >
                            <span>${escapeHtml(item.question)}</span>
                        </button>
                    </h2>
                    <div
                        id="panel-${item.id}"
                        class="accordion-collapse"
                        role="region"
                        aria-labelledby="heading-${item.id}"
                    >
                        <div class="accordion-body">${item.answer}</div>
                    </div>
                </div>
            `,
        )
        .join('');
}

function bindFaqSearch(main) {
    const input = main.querySelector('[data-faq-search]');
    const emptyState = main.querySelector('[data-faq-empty]');
    const items = Array.from(main.querySelectorAll('[data-faq-question]'));

    if (!input || items.length === 0) {
        return;
    }

    const update = () => {
        const term = input.value.trim().toLowerCase();
        let visibleItems = 0;

        items.forEach((item) => {
            const matches = term === '' || item.dataset.faqQuestion.includes(term);
            item.hidden = !matches;

            if (matches) {
                visibleItems += 1;
            }
        });

        if (emptyState) {
            emptyState.hidden = visibleItems !== 0;
        }
    };

    input.addEventListener('input', update);
}

function bindFaqAccordion(main) {
    const buttons = Array.from(main.querySelectorAll('[data-faq-toggle]'));

    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            const item = button.closest('.accordion-item');
            const panel = item?.querySelector('.accordion-collapse');

            if (!item || !panel) {
                return;
            }

            const willOpen = !item.classList.contains('is-open');

            buttons.forEach((otherButton) => {
                const otherItem = otherButton.closest('.accordion-item');
                const otherPanel = otherItem?.querySelector('.accordion-collapse');

                if (!otherItem || !otherPanel) {
                    return;
                }

                otherItem.classList.remove('is-open');
                otherButton.setAttribute('aria-expanded', 'false');
                otherPanel.hidden = true;
            });

            item.classList.toggle('is-open', willOpen);
            button.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
            panel.hidden = !willOpen;
        });
    });

    main.querySelectorAll('.accordion-collapse').forEach((panel) => {
        panel.hidden = true;
    });
}

function bindFaqRating(main) {
    const buttons = Array.from(main.querySelectorAll('[data-rating-choice]'));
    const feedback = main.querySelector('[data-rating-feedback]');

    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            buttons.forEach((otherButton) => {
                otherButton.classList.toggle('is-selected', otherButton === button);
            });

            if (feedback) {
                feedback.hidden = false;
                feedback.textContent = button.dataset.ratingMessage || 'Grazie per il feedback.';
            }
        });
    });
}

export function domandeFrequentiParity() {
    if (!window.location.pathname.endsWith('/it/tests/domande-frequenti')) {
        return;
    }

    const main = document.querySelector('main#main-container');

    if (!main) {
        return;
    }

    const title = main.querySelector('h1')?.textContent.trim() || 'Domande frequenti';
    const description = 'Elenco di risposte alle domande più frequenti raccolte dalle richieste di assistenza dei cittadini.';
    const faqItems = collectFaqItems(main);

    if (faqItems.length === 0) {
        return;
    }

    document.body.classList.add('dc-faq-parity');

    main.innerHTML = `
        <section class="dc-faq-breadcrumb-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10">
                        <div class="cmp-breadcrumbs" role="navigation">
                            <nav class="breadcrumb-container" aria-label="breadcrumb">
                                <ol class="breadcrumb p-0" data-element="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="/it/tests/homepage">Home</a>
                                        <span class="separator">/</span>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">${escapeHtml(title)}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="dc-faq-hero-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10">
                        <div class="cmp-hero">
                            <section class="it-hero-wrapper bg-white align-items-start">
                                <div class="it-hero-text-wrapper pt-0 ps-0 pb-4 pb-lg-60">
                                    <h1 class="text-black" data-element="page-name">${escapeHtml(title)}</h1>
                                    <div class="hero-text">
                                        <p>${escapeHtml(description)}</p>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="dc-faq-search-section">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-8 offset-lg-2 px-sm-3 mt-2">
                        <div class="cmp-input-search">
                            <div class="form-group autocomplete-wrapper mb-2 mb-lg-4">
                                <div class="input-group">
                                    <label for="domande-frequenti-search" class="visually-hidden">Cerca nelle FAQ</label>
                                    <input
                                        type="search"
                                        class="autocomplete form-control"
                                        placeholder="Cerca"
                                        id="domande-frequenti-search"
                                        name="domande-frequenti-search"
                                        data-faq-search
                                    >
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button">Invio</button>
                                    </div>
                                    <span class="autocomplete-icon" aria-hidden="true">
                                        <svg class="icon icon-sm icon-primary">
                                            <use href="${SPRITE_PATH}#it-search"></use>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="dc-faq-accordion-section">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-8 offset-lg-2 px-0 px-sm-3">
                        <div class="cmp-accordion faq">
                            <div class="accordion" id="accordion-faq">
                                ${renderFaqItems(faqItems)}
                            </div>
                            <p class="dc-faq-empty" data-faq-empty hidden>Nessun risultato trovato.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="dc-faq-rating-shell">
            <div class="bg-primary">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-6">
                            <div class="cmp-rating pt-lg-80 pb-lg-80" id="rating">
                                <div class="card shadow card-wrapper" data-element="feedback">
                                    <div class="cmp-rating__card-first">
                                        <div class="card-header border-0">
                                            <h2 class="title-medium-2-semi-bold mb-0" data-element="feedback-title">Quanto sono chiare le informazioni su questa pagina?</h2>
                                        </div>
                                        <div class="card-body">
                                            <div class="rating rating-stars" aria-label="Valuta da 1 a 5 stelle la pagina">
                                                <button type="button" class="rating-star" data-rating-choice data-rating-message="Grazie, il tuo feedback e stato registrato." aria-label="Valuta 1 stella su 5"><span aria-hidden="true">★</span></button>
                                                <button type="button" class="rating-star" data-rating-choice data-rating-message="Grazie, il tuo feedback e stato registrato." aria-label="Valuta 2 stelle su 5"><span aria-hidden="true">★</span></button>
                                                <button type="button" class="rating-star" data-rating-choice data-rating-message="Grazie, il tuo feedback e stato registrato." aria-label="Valuta 3 stelle su 5"><span aria-hidden="true">★</span></button>
                                                <button type="button" class="rating-star" data-rating-choice data-rating-message="Grazie, il tuo feedback e stato registrato." aria-label="Valuta 4 stelle su 5"><span aria-hidden="true">★</span></button>
                                                <button type="button" class="rating-star" data-rating-choice data-rating-message="Grazie, il tuo feedback e stato registrato." aria-label="Valuta 5 stelle su 5"><span aria-hidden="true">★</span></button>
                                            </div>
                                            <p class="dc-faq-rating-feedback" data-rating-feedback hidden></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="dc-faq-contacts-shell">
            <div class="bg-grey-card shadow-contacts">
                <div class="container">
                    <div class="row d-flex justify-content-center p-contacts">
                        <div class="col-12 col-lg-6">
                            <div class="cmp-contacts">
                                <div class="card w-100">
                                    <div class="card-body">
                                        <h2 class="title-medium-2-semi-bold">Contatta il comune</h2>
                                        <ul class="contact-list p-0">
                                            <li><a class="list-item" href="/it/tests/assistenza-01-dati"><svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="${SPRITE_PATH}#it-mail"></use></svg><span>Richiedi assistenza</span></a></li>
                                            <li><a class="list-item" href="tel:050505"><svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="${SPRITE_PATH}#it-hearing"></use></svg><span>Chiama il numero verde 05 0505</span></a></li>
                                            <li><a class="list-item" href="/it/tests/appuntamento-01-ufficio"><svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="${SPRITE_PATH}#it-calendar"></use></svg><span>Prenota appuntamento</span></a></li>
                                        </ul>
                                        <h2 class="title-medium-2-semi-bold mt-4">Problemi in citta</h2>
                                        <ul class="contact-list p-0">
                                            <li><a class="list-item" href="/it/tests/segnalazione-dettaglio"><svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="${SPRITE_PATH}#it-map-marker-circle"></use></svg><span>Segnala disservizio</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    `;

    bindFaqSearch(main);
    bindFaqAccordion(main);
    bindFaqRating(main);
}
