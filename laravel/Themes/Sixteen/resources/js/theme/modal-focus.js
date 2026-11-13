/**
 * Focus delle modali Design Comuni (`.modal.fade`, es. la ricerca dell'intestazione), WCAG 2.4.3 e 2.1.2 (DCP-288, FO-1-07).
 *
 * Il codice che apre e chiude le modali (app.js) aggiunge e toglie solo `.show`. Qui si guarda quella classe, cosi' vale per qualunque
 * percorso di apertura: all'apertura il focus entra nella modale (nel primo campo, se c'e'), Tab e Maiusc+Tab restano dentro,
 * alla chiusura (Esc, pulsante, clic sullo sfondo) il focus torna al pulsante che l'aveva aperta.
 * Le modali che gestiscono gia' il focus da sole (#modal-categories in app.js) non sono toccate: il focus e' gia' dentro all'apertura,
 * il loro tasto Tab ha gia' chiamato preventDefault e il loro pulsante non ha `data-bs-toggle` (nessun opener registrato).
 */
const FOCUSABLE =
    'a[href], button, input, select, textarea, [tabindex]:not([tabindex="-1"])';
const TEXT_ENTRY =
    'input:not([type="hidden"]):not([type="checkbox"]):not([type="radio"]):not([type="button"]):not([type="submit"]), textarea, select';

/** Pulsante che ha aperto ogni modale: serve per riportargli il focus alla chiusura. */
const openers = new WeakMap();
/** Modali gia' osservate: `initModalFocus` puo' essere richiamata (livewire:navigated) senza duplicare gli osservatori. */
const observed = new WeakSet();

const isVisible = element =>
    element.getClientRects().length > 0 &&
    getComputedStyle(element).visibility !== 'hidden';

/**
 * @param {Element} root
 * @returns {HTMLElement[]}
 */
function focusableIn(root) {
    return Array.from(root.querySelectorAll(FOCUSABLE)).filter(
        element =>
            !element.disabled &&
            element.getAttribute('aria-hidden') !== 'true' &&
            isVisible(element)
    );
}

/** @param {HTMLElement} modal */
function moveFocusInto(modal) {
    if (modal.contains(document.activeElement)) {
        return;
    }

    const target =
        Array.from(modal.querySelectorAll(TEXT_ENTRY)).find(isVisible) ??
        focusableIn(modal)[0] ??
        modal;
    target.focus({ preventScroll: true });
}

/** @param {HTMLElement} modal */
function returnFocusFrom(modal) {
    const opener = openers.get(modal);
    openers.delete(modal);

    if (opener && opener.isConnected && isVisible(opener)) {
        opener.focus({ preventScroll: true });
    }
}

/** @param {KeyboardEvent} event */
function keepTabInsideOpenModal(event) {
    if (event.key !== 'Tab' || event.defaultPrevented) {
        return;
    }

    const modal = Array.from(document.querySelectorAll('.modal.show')).pop();
    if (!modal) {
        return;
    }

    const items = focusableIn(modal);
    const active = document.activeElement;
    if (items.length === 0) {
        event.preventDefault();
        modal.focus({ preventScroll: true });

        return;
    }

    const first = items[0];
    const last = items[items.length - 1];
    const outside = !modal.contains(active) || active === modal;
    if (event.shiftKey && (outside || active === first)) {
        event.preventDefault();
        last.focus();
    } else if (!event.shiftKey && (outside || active === last)) {
        event.preventDefault();
        first.focus();
    }
}

/**
 * Prima del gestore di app.js (fase di cattura): se la modale e' chiusa, il pulsante cliccato (anche con Invio o Spazio) e' l'opener.
 *
 * @param {MouseEvent} event
 */
function rememberOpener(event) {
    const trigger =
        event.target instanceof Element
            ? event.target.closest('[data-bs-toggle="modal"]')
            : null;
    const selector = trigger?.getAttribute('data-bs-target');
    const modal =
        selector && selector !== '#' ? document.querySelector(selector) : null;

    if (trigger && modal && !modal.classList.contains('show')) {
        openers.set(modal, trigger);
    }
}

/** @param {HTMLElement} modal */
function observeModal(modal) {
    if (observed.has(modal)) {
        return;
    }

    observed.add(modal);
    let wasOpen = modal.classList.contains('show');
    new MutationObserver(() => {
        const isOpen = modal.classList.contains('show');
        if (isOpen === wasOpen) {
            return;
        }

        wasOpen = isOpen;
        if (isOpen) {
            moveFocusInto(modal);
        } else {
            returnFocusFrom(modal);
        }
    }).observe(modal, { attributes: true, attributeFilter: ['class'] });
}

export function initModalFocus() {
    if (!document.documentElement.hasAttribute('data-modal-focus')) {
        document.documentElement.setAttribute('data-modal-focus', '');
        document.addEventListener('click', rememberOpener, true);
        document.addEventListener('keydown', keepTabInsideOpenModal);
    }

    document.querySelectorAll('.modal').forEach(observeModal);
}
