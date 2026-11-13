// Pagine /tests: qualunque toggler/pannello `navbarcollapsible` del riferimento (es. `#navbarNavB` del catalogo), non solo `#nav4`
<<<<<<< HEAD
const withDesignComuni = (base, extra) =>
    isDesignComuniPage() ? `${base}, ${extra}` : base;
const navToggleSelector = () =>
    withDesignComuni(
        '.custom-navbar-toggler[data-bs-target="#nav4"], [data-mobile-nav-toggle]',
        '[data-bs-toggle="navbarcollapsible"]'
    );
const navPanelSelector = () =>
    withDesignComuni(
        '#nav4.navbar-collapsable, [data-mobile-nav-panel]',
        '.navbar-collapsable'
    );
const navOverlaySelector = () =>
    '.navbar-collapsable > .overlay, [data-mobile-nav-overlay]';
const navCloseSelector = () =>
    '.navbar-collapsable .close-menu, [data-mobile-nav-close]';
=======
const withDesignComuni = (base, extra) => (isDesignComuniPage() ? `${base}, ${extra}` : base);
const navToggleSelector = () => withDesignComuni('.custom-navbar-toggler[data-bs-target="#nav4"], [data-mobile-nav-toggle]', '[data-bs-toggle="navbarcollapsible"]');
const navPanelSelector = () => withDesignComuni('#nav4.navbar-collapsable, [data-mobile-nav-panel]', '.navbar-collapsable');
const navOverlaySelector = () => '.navbar-collapsable > .overlay, [data-mobile-nav-overlay]';
const navCloseSelector = () => '.navbar-collapsable .close-menu, [data-mobile-nav-close]';
>>>>>>> f08c6884 (.)
const panelOpeners = new WeakMap();

/**
 * Dove valgono i comportamenti Design Comuni (menu `expanded`, modali con backdrop, tendine posizionate): pagine con il wrapper `data-dc` (CSS generato)
 * oppure con l'header Design Comuni (`layouts/app` sulle pagine reali: il menu mobile deve funzionare anche li', DCP-121).
 */
export const isDesignComuniPage = () =>
    document.querySelector(
        '.page-content[data-dc], header.it-header-wrapper'
    ) !== null;

function getTargetPanel(toggle) {
<<<<<<< HEAD
    const selector =
        toggle.getAttribute('data-bs-target') ??
        toggle.getAttribute('data-mobile-nav-target');
=======
    const selector = toggle.getAttribute('data-bs-target')
        ?? toggle.getAttribute('data-mobile-nav-target');
>>>>>>> f08c6884 (.)

    return selector
        ? document.querySelector(selector)
        : document.querySelector('#nav4');
}

function getRelatedOverlays(panel) {
<<<<<<< HEAD
    const root =
        panel.closest('[data-mobile-nav]') ||
        panel.closest('.it-nav-wrapper') ||
        document;
=======
    const root = panel.closest('[data-mobile-nav]') || panel.closest('.it-nav-wrapper') || document;
>>>>>>> f08c6884 (.)
    const overlays = root.querySelectorAll(navOverlaySelector());

    return overlays.length > 0
        ? overlays
        : document.querySelectorAll(navOverlaySelector());
}

function updateBodyLock() {
    // `.is-open` va su OGNI selettore della lista: prima si accodava solo all'ultimo e `#nav4.navbar-collapsable` (sempre presente) teneva `nav-open` sul body anche a menu chiuso
    const openPanels = navPanelSelector()
        .split(',')
        .map(part => `${part.trim()}.is-open`)
        .join(', ');
    document.body.classList.toggle(
        'nav-open',
        document.querySelector(openPanels) !== null
    );
}

<<<<<<< HEAD
const isDesktopViewport = () =>
    typeof window.matchMedia === 'function' &&
    window.matchMedia('(min-width: 992px)').matches;

/**
 * `aria-hidden` descrive il pannello solo quando e' un drawer (mobile): dal breakpoint lg e' la barra di navigazione sempre
 * visibile e i suoi link restano focusabili, quindi `aria-hidden="true"` li nasconderebbe alle tecnologie assistive
 * (axe `aria-hidden-focus`, WCAG 4.1.2). Va rivalutato anche al cambio di viewport, non solo all'apertura.
 */
function syncPanelAria(panel) {
    if (isDesktopViewport()) {
        panel.removeAttribute('aria-hidden');

        return;
    }

    panel.setAttribute(
        'aria-hidden',
        panel.classList.contains('is-open') ? 'false' : 'true'
    );
}

=======
>>>>>>> f08c6884 (.)
function setPanelOpen(panel, open, focusFirstLink = false, opener = null) {
    if (!panel) {
        return;
    }

    if (open && opener) {
        panelOpeners.set(panel, opener);
    }

    panel.classList.toggle('is-open', open);
    panel.classList.toggle('show', open);
<<<<<<< HEAD
    syncPanelAria(panel);
=======
    panel.setAttribute('aria-hidden', open ? 'false' : 'true');
>>>>>>> f08c6884 (.)
    // pagine Design Comuni (/tests, stesso ambito del CSS generato): `expanded`, display e aria-expanded sul pannello, come nel riferimento
    if (isDesignComuniPage()) {
        panel.classList.toggle('expanded', open);
        panel.style.display = open ? 'block' : '';
        if (open) {
            panel.setAttribute('aria-expanded', 'true');
        } else {
            panel.removeAttribute('aria-expanded');
        }
        document.documentElement.classList.toggle(
            'pagescroll-scroll-disabled',
            open
        );
    }

    getRelatedOverlays(panel).forEach(overlay => {
        overlay.hidden = !open;
        overlay.style.display = open ? 'block' : 'none';
        overlay.classList.toggle('is-open', open);
        overlay.classList.toggle('show', open);
        if (isDesignComuniPage()) {
            overlay.classList.toggle('fade', open);
        }
    });

    document.querySelectorAll(navToggleSelector()).forEach(toggle => {
        if (getTargetPanel(toggle) === panel) {
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        }
    });

    updateBodyLock();

    if (!open) {
        const previousOpener = opener ?? panelOpeners.get(panel);
<<<<<<< HEAD
        if (
            previousOpener?.isConnected &&
            panel.contains(document.activeElement)
        ) {
=======
        if (previousOpener?.isConnected && panel.contains(document.activeElement)) {
>>>>>>> f08c6884 (.)
            previousOpener.focus();
        }
        panelOpeners.delete(panel);
    }

    if (open && focusFirstLink) {
        window.requestAnimationFrame(() => {
            panel.querySelector('.menu-wrapper a, a, button')?.focus();
        });
    }
}

function closeAllPanels() {
<<<<<<< HEAD
    document.querySelectorAll(navPanelSelector()).forEach(panel => {
=======
    document.querySelectorAll(navPanelSelector()).forEach((panel) => {
>>>>>>> f08c6884 (.)
        setPanelOpen(panel, false, false, panelOpeners.get(panel));
    });
}

function getFocusableElements(panel) {
<<<<<<< HEAD
    return [
        ...panel.querySelectorAll(
            'a[href], area[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
        ),
    ].filter(
        element =>
            element.offsetParent !== null || element === document.activeElement
    );
=======
    return [...panel.querySelectorAll(
        'a[href], area[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
    )].filter((element) => element.offsetParent !== null || element === document.activeElement);
>>>>>>> f08c6884 (.)
}

function keepFocusInsideOpenPanel(event) {
    if (event.key !== 'Tab') {
        return;
    }

<<<<<<< HEAD
    const panel = document.querySelector(
        `${navPanelSelector()
            .split(',')
            .map(part => `${part.trim()}.is-open`)
            .join(', ')}`
    );
=======
    const panel = document.querySelector(`${navPanelSelector().split(',').map((part) => `${part.trim()}.is-open`).join(', ')}`);
>>>>>>> f08c6884 (.)
    if (!panel) {
        return;
    }

    const focusable = getFocusableElements(panel);
    if (focusable.length === 0) {
        return;
    }

    const first = focusable[0];
    const last = focusable[focusable.length - 1];
    if (!panel.contains(document.activeElement)) {
        event.preventDefault();
        first.focus();
    } else if (event.shiftKey && document.activeElement === first) {
        event.preventDefault();
        last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
        event.preventDefault();
        first.focus();
    }
}

export function initHeaderMobileNav() {
<<<<<<< HEAD
    document.querySelectorAll(navPanelSelector()).forEach(syncPanelAria);

    document.querySelectorAll(navToggleSelector()).forEach(toggle => {
=======
    document.querySelectorAll(navPanelSelector()).forEach((panel) => {
        panel.setAttribute('aria-hidden', panel.classList.contains('is-open') ? 'false' : 'true');
    });

    document.querySelectorAll(navToggleSelector()).forEach((toggle) => {
>>>>>>> f08c6884 (.)
        if (toggle.dataset.mobileNavBound === 'true') {
            return;
        }

        toggle.dataset.mobileNavBound = 'true';
<<<<<<< HEAD
        toggle.addEventListener('click', event => {
            event.preventDefault();
            const panel = getTargetPanel(toggle);
            setPanelOpen(
                panel,
                !panel?.classList.contains('is-open'),
                true,
                toggle
            );
        });
    });

    document.querySelectorAll(navOverlaySelector()).forEach(overlay => {
=======
        toggle.addEventListener('click', (event) => {
            event.preventDefault();
            const panel = getTargetPanel(toggle);
            setPanelOpen(panel, !(panel?.classList.contains('is-open')), true, toggle);
        });
    });

    document.querySelectorAll(navOverlaySelector()).forEach((overlay) => {
>>>>>>> f08c6884 (.)
        if (overlay.dataset.mobileNavBound === 'true') {
            return;
        }

        overlay.dataset.mobileNavBound = 'true';
        overlay.addEventListener('click', closeAllPanels);
    });

<<<<<<< HEAD
    document.querySelectorAll(navCloseSelector()).forEach(closeButton => {
=======
    document.querySelectorAll(navCloseSelector()).forEach((closeButton) => {
>>>>>>> f08c6884 (.)
        if (closeButton.dataset.mobileNavBound === 'true') {
            return;
        }

        closeButton.dataset.mobileNavBound = 'true';
<<<<<<< HEAD
        closeButton.addEventListener('click', event => {
            event.preventDefault();
            const panel =
                closeButton.closest(navPanelSelector()) ||
                document.querySelector(navPanelSelector());
=======
        closeButton.addEventListener('click', (event) => {
            event.preventDefault();
            const panel = closeButton.closest(navPanelSelector()) || document.querySelector(navPanelSelector());
>>>>>>> f08c6884 (.)
            setPanelOpen(panel, false, false, panelOpeners.get(panel));
        });
    });
}

if (typeof document !== 'undefined') {
<<<<<<< HEAD
    document.addEventListener('keydown', event => {
=======
    document.addEventListener('keydown', (event) => {
>>>>>>> f08c6884 (.)
        keepFocusInsideOpenPanel(event);
        if (event.key === 'Escape') {
            closeAllPanels();
        }
    });
}

if (typeof window !== 'undefined' && typeof window.matchMedia === 'function') {
    window
        .matchMedia('(min-width: 992px)')
        .addEventListener('change', event => {
            if (event.matches) {
                closeAllPanels();
            }
            // anche nel verso desktop -> mobile: il drawer richiuso torna `aria-hidden="true"`
            document
                .querySelectorAll(navPanelSelector())
                .forEach(syncPanelAria);
        });
}
