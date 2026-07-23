/**
 * Sixteen Theme - App JavaScript
 *
 * Design Comuni replicated with Tailwind CSS + Alpine.js
 * NO Bootstrap Italia - Pure Tailwind + Alpine implementation
 *
 * CRITICAL: Livewire/Filament already loads Alpine.js.
 * We must NOT create a second instance. Only extend the existing one.
 *
 * Dark mode: boot anti-FOUC in layouts/main.blade.php (head); logica toggle in theme/dark-mode.js.
 */

import '@modules/Geo/resources/js/components/map-lit.js';
import '@modules/Geo/resources/js/components/map-filter-lit.js';
import '@modules/Geo/resources/js/components/coordinate-picker-lit.js';

import '@splidejs/splide/dist/css/splide.min.css';
import '@theme-leaflet-css';
import { initDarkModeToggle, toggleDarkMode } from './theme/dark-mode.js';
import focus from '@alpinejs/focus';
import { dropdownToggle } from './components/dropdown';
import { modal } from './components/modal';
import { mobileMenu } from './components/mobile-menu';
import { governanceCarousel } from './components/carousel';
import './components/bootstrap-italia.js';
import { initHeaderMobileNav } from './theme/header-mobile-nav.js';
// DISABLED: domande-frequenti-parity.js was overriding blade template HTML with JS-generated structure
// Now using blade template directly with Alpine.js for accordion
// import { domandeFrequentiParity } from './domande-frequenti-parity';

/**
 * Register theme Alpine components onto Livewire/Filament’s Alpine bundle.
 *
 * IMPORTANT: questo file e' un ES module (defer). Il markup iniziale non deve
 * dipendere da Alpine.data registrati qui; l'header usa il controller
 * data-sixteen-mobile-nav importato in questo bundle Vite.
 *
 * @param {object} AlpineInstance
 */
function registerAlpineComponents(AlpineInstance) {
    if (!AlpineInstance || document.documentElement.hasAttribute('data-sixteen-alpine-components')) {
        return;
    }

    AlpineInstance.plugin(focus);

    AlpineInstance.data('dropdownToggle', dropdownToggle);
    AlpineInstance.data('modal', modal);
    AlpineInstance.data('mobileMenu', mobileMenu);
    AlpineInstance.data('governanceCarousel', governanceCarousel);

    AlpineInstance.data('dropdown', () => ({
        open: false,
        toggle() {
            this.open = !this.open;
        },
    }));

    // Work around Alpine 3.15.x object-literal issues inside inline x-data.
    AlpineInstance.data('accordionItem', () => ({ open: false }));
    AlpineInstance.data('ratingInline', () => ({ rating: 0, hover: 0 }));

    AlpineInstance.data('ticketLayout', () => ({
        activeTab: 'map',
        showModal: false,
        showFilterModal: false,
        init() {
            this.$watch('activeTab', (tab) => {
                if (tab !== 'map') { return; }
                setTimeout(() => {
                    const mapEl = document.getElementById('block-map');
                    if (mapEl && typeof mapEl.invalidateSize === 'function') {
                        mapEl.invalidateSize();
                    }
                }, 50);
            });
            this.$watch('$store.ticketTabs.active', (tab) => {
                this.activeTab = tab;
            });
        },
    }));

    // Dark mode — state + toggle (persistito in localStorage)
    AlpineInstance.data('darkMode', () => ({
        isDark: document.documentElement.classList.contains('dark'),
        toggle() {
            toggleDarkMode();
            this.isDark = document.documentElement.classList.contains('dark');
        },
    }));

    document.documentElement.setAttribute('data-sixteen-alpine-components', 'true');
}

if (window.Alpine) {
     registerAlpineComponents(window.Alpine);
 } else {
     document.addEventListener('alpine:init', () => {
         registerAlpineComponents(window.Alpine);
     }, { once: true });
 }

// Bootstrap tabs shim (no Bootstrap JS): invalidate map when map tab is shown
document.addEventListener('shown.bs.tab', function (e) {
    const target = e.target;
    let targetId = target && typeof target.getAttribute === 'function'
        ? (target.getAttribute('href') || target.getAttribute('data-bs-target'))
        : null;
    if (!targetId && e.detail?.relatedTarget?.id) {
        targetId = '#' + e.detail.relatedTarget.id;
    }
    if (targetId && targetId.includes('disservizio1')) {
        setTimeout(() => {
            const mapEl = document.getElementById('block-map');
            if (mapEl && typeof mapEl.invalidateSize === 'function') {
                mapEl.invalidateSize();
            }
        }, 100);
    }
});

document.addEventListener('alpine:init', () => {
    const A = window.Alpine;
    if (!A) { return; }
    if (!A.store('ticketTabs')) {
        A.store('ticketTabs', {
            active: 'map',
            setTab(tab) {
                this.active = tab;
                if (tab === 'map') {
                    setTimeout(() => {
                        const mapEl = document.getElementById('block-map');
                        if (mapEl && typeof mapEl.invalidateSize === 'function') {
                            mapEl.invalidateSize();
                        }
                    }, 50);
                }
            },
        });
    }
});

initHeaderMobileNav();

// Scope modulo — disponibili a initHeaderDropdowns e ai listener globali (ESC, click esterno)
function closeDropdownMenu(menu) {
    if (!menu) { return; }
    menu.classList.remove('show');
    menu.style.display = 'none';
    const openDropdown = menu.closest('.dropdown');
    openDropdown?.classList.remove('is-open');
    openDropdown?.querySelector('[data-bs-toggle="dropdown"]')?.setAttribute('aria-expanded', 'false');
}

function openDropdownMenu(menu, dropdown, toggle) {
    if (!menu || !toggle) { return; }
    menu.classList.add('show');
    menu.style.display = 'block';
    dropdown?.classList.add('is-open');
    toggle.setAttribute('aria-expanded', 'true');
}

// Re-aggancia i listener dopo ogni DOM morph di Livewire 4
function initHeaderDropdowns() {
    // Slim header: Bootstrap 5 Dropdown (bootstrap-italia.js). Fallback manuale se assente.
    if (window.bootstrap?.Dropdown) {
        document.querySelectorAll('.it-header-slim-wrapper [data-bs-toggle="dropdown"]').forEach(function(toggle) {
            window.bootstrap.Dropdown.getOrCreateInstance(toggle);
        });
        return;
    }

    document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function(toggle) {
        if (toggle._headerDropdownInit) { return; }
        toggle._headerDropdownInit = true;

        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const dropdown = this.closest('.dropdown');
            const menu = dropdown?.querySelector('.dropdown-menu');
            const willOpen = menu ? !menu.classList.contains('show') : false;

            document.querySelectorAll('.dropdown-menu.show').forEach(function(openMenu) {
                if (openMenu !== menu) { closeDropdownMenu(openMenu); }
            });

            if (menu) {
                willOpen ? openDropdownMenu(menu, dropdown, this) : closeDropdownMenu(menu);
            }
        });
    });

    document.querySelectorAll('.dropdown-menu').forEach(function(menu) {
        if (menu._headerDropdownMenuInit) { return; }
        menu._headerDropdownMenuInit = true;
        menu.addEventListener('click', function(e) { e.stopPropagation(); });
    });
}

document.addEventListener('DOMContentLoaded', initHeaderDropdowns);
document.addEventListener('livewire:navigated', initHeaderDropdowns);
document.addEventListener('livewire:update', initHeaderDropdowns);
if (document.readyState !== 'loading') {
    initHeaderDropdowns();
}

document.addEventListener('DOMContentLoaded', initDarkModeToggle);
document.addEventListener('livewire:navigated', initDarkModeToggle);
document.addEventListener('DOMContentLoaded', initHeaderMobileNav);
document.addEventListener('livewire:navigated', initHeaderMobileNav);
document.addEventListener('livewire:update', initHeaderMobileNav);

// STORY-365: modal filtri mobile — Bootstrap Italia ha display:none !important su .modal,
// che batte il style.display JS senza !important. Bypass completo: teleport a body +
// setProperty con 'important' + backdrop manuale.
function initFilterModal() {
    var modal = document.getElementById('modal-categories');
    if (!modal) { return; }

    document.body.appendChild(modal);

    function openFilterModal() {
        var mapEl = document.getElementById('ticket-map');
        if (mapEl && mapEl._map) { mapEl._map.closePopup(); }
        modal.style.setProperty('display', 'block', 'important');
        modal.style.setProperty('visibility', 'visible', 'important');
        modal.classList.add('show');
        modal.removeAttribute('aria-hidden');
        modal.setAttribute('aria-modal', 'true');
        document.body.classList.add('modal-open');
        var bd = document.createElement('div');
        bd.className = 'modal-backdrop show';
        bd.id = '_mfix_bd';
        document.body.appendChild(bd);
        bd.addEventListener('click', closeFilterModal);
    }

    function closeFilterModal() {
        // Sposta focus prima di aria-hidden per evitare warning accessibilità
        var trigger = document.querySelector('[data-bs-target="#modal-categories"]');
        if (trigger) { trigger.focus(); }
        modal.style.removeProperty('display');
        modal.style.removeProperty('visibility');
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
        modal.removeAttribute('aria-modal');
        document.body.classList.remove('modal-open');
        var bd = document.getElementById('_mfix_bd');
        if (bd) { bd.remove(); }
    }

    document.querySelectorAll('[data-bs-target="#modal-categories"]').forEach(function (btn) {
        btn.removeAttribute('data-bs-toggle'); // disabilita Bootstrap delegation
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            openFilterModal();
        });
    });

    modal.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function (btn) {
        btn.addEventListener('click', closeFilterModal);
    });
}

// Esegue prima di DOMContentLoaded per prevenire Bootstrap delegation su #modal-categories
if (document.readyState !== 'loading') {
    initFilterModal();
} else {
    document.addEventListener('DOMContentLoaded', initFilterModal, { once: true });
}

document.addEventListener('DOMContentLoaded', function() {

    const closeModal = function(modal) {
        if (!modal) {
            return;
        }

        modal.classList.remove('show');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    };

    const openModal = function(modal) {
        if (!modal) {
            return;
        }

        modal.classList.add('show');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    };

    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-bs-target');
            const modal = document.querySelector(targetId);

            if (!modal) {
                return;
            }

            if (modal.classList.contains('show')) {
                closeModal(modal);
            } else {
                openModal(modal);
            }
        });
    });

    document.querySelectorAll('[data-bs-dismiss="modal"], .modal .btn-close').forEach(function(btn) {
        btn.addEventListener('click', function() {
            closeModal(this.closest('.modal'));
        });
    });

    document.querySelectorAll('.modal').forEach(function(modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this);
            }
        });
    });

    document.addEventListener('click', function(e) {
        const target = e.target;
        if (target instanceof Element) {
            if (target.closest('[data-bs-toggle="dropdown"]') || target.closest('.dropdown-menu')) {
                return;
            }
        }

        document.querySelectorAll('.dropdown-menu.show').forEach(function(openMenu) {
            closeDropdownMenu(openMenu);
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key !== 'Escape') {
            return;
        }

        document.querySelectorAll('.dropdown-menu.show').forEach(function(openMenu) {
            closeDropdownMenu(openMenu);
        });

        document.querySelectorAll('.modal.show').forEach(function(modal) {
            closeModal(modal);
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();

            const targetId = this.getAttribute('data-bs-target');
            const panel = targetId ? document.querySelector(targetId) : null;

            if (!panel) {
                return;
            }

            const parentSelector = panel.getAttribute('data-bs-parent');
            const isOpen = panel.classList.contains('show');

            if (parentSelector) {
                document.querySelectorAll(parentSelector + ' .accordion-collapse.show').forEach(function(openPanel) {
                    if (openPanel !== panel) {
                        openPanel.classList.remove('show');
                        openPanel.previousElementSibling?.querySelector('.accordion-button')?.classList.add('collapsed');
                        openPanel.previousElementSibling?.querySelector('.accordion-button')?.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            panel.classList.toggle('show', !isOpen);
            this.classList.toggle('collapsed', isOpen);
            this.setAttribute('aria-expanded', String(!isOpen));
        });
    });

    initTicketTabs();
    initTicketFilters();
    scheduleMapLitBootstrap();
});

/**
 * Attende upgrade di map-lit (module ES defer) e refresh dimensioni mappa.
 */
function scheduleMapLitBootstrap() {
    const refresh = (attempt = 0) => {
        const mapEl = document.querySelector('map-lit#block-map');
        if (!mapEl) {
            return;
        }

        if (typeof mapEl.refreshWhenVisible === 'function') {
            mapEl.refreshWhenVisible();
            if (mapEl._map && mapEl._allMarkers?.length > 0) {
                return;
            }
        } else if (typeof mapEl.invalidateSize === 'function') {
            mapEl.invalidateSize();
            if (mapEl._map && mapEl._allMarkers?.length > 0) {
                return;
            }
        }

        if (attempt < 24) {
            window.setTimeout(() => refresh(attempt + 1), 250);
        }
    };

    refresh();
}

/**
 * Tab Mappa/Elenco senza Bootstrap JS (Design Comuni markup con data-bs-toggle="tab").
 */
function initTicketTabs() {
    const tabList = document.getElementById('tabDisservizio');
    if (!tabList) {
        return;
    }

    const tabContent = tabList.parentElement?.querySelector('.tab-content');
    if (!tabContent) {
        return;
    }

    const tabLinks = tabList.querySelectorAll('.nav-link[data-bs-toggle="tab"]');
    const tabPanes = tabContent.querySelectorAll('.tab-pane');

    const activateTab = (link) => {
        const targetId = link.getAttribute('href') || link.getAttribute('data-bs-target');
        if (!targetId) {
            return;
        }

        const targetPane = tabContent.querySelector(targetId);
        if (!targetPane) {
            return;
        }

        tabLinks.forEach((tab) => {
            tab.classList.remove('active');
            tab.setAttribute('aria-selected', 'false');
        });
        tabPanes.forEach((pane) => {
            pane.classList.remove('show', 'active');
        });

        link.classList.add('active');
        link.setAttribute('aria-selected', 'true');
        targetPane.classList.add('show', 'active');

        document.dispatchEvent(new CustomEvent('shown.bs.tab', {
            bubbles: true,
            detail: { relatedTarget: targetPane },
        }));

        const mapEl = targetPane.querySelector('map-lit#block-map, map-lit')
            || document.getElementById('block-map');
        if (mapEl && typeof mapEl.refreshWhenVisible === 'function') {
            [0, 150, 400].forEach((delay) => {
                setTimeout(() => mapEl.refreshWhenVisible(), delay);
            });
        } else if (mapEl && typeof mapEl.invalidateSize === 'function') {
            [0, 150, 400].forEach((delay) => {
                setTimeout(() => mapEl.invalidateSize(), delay);
            });
        }
    };

    tabLinks.forEach((link) => {
        if (link._segnalazioniTabInit) {
            return;
        }
        link._segnalazioniTabInit = true;
        link.addEventListener('click', (event) => {
            event.preventDefault();
            activateTab(link);
        });
    });
}

/**
 * Filtri sidebar (tipologia + stato) → map-lit + conteggio risultati.
 * Fonte primaria: aside desktop (evita doppio conteggio modale mobile).
 */
function initTicketFilters() {
    const root = document.getElementById('main-container');
    if (!root) {
        return;
    }

    const desktopRoot = root.querySelector('aside.col-lg-3, div.col-lg-3.d-none.d-lg-block');
    const modalRoot = root.querySelector('#modal-categories') || document.getElementById('modal-categories');
    const typeSelector = 'input[type="checkbox"][name="category"][data-filter-type]';
    const statusSelector = 'input[type="checkbox"][name="status"][data-filter-status]';
    const primaryTypeCheckboxes = desktopRoot
        ? [...desktopRoot.querySelectorAll(typeSelector)]
        : [...root.querySelectorAll(typeSelector)];
    const primaryStatusCheckboxes = desktopRoot
        ? [...desktopRoot.querySelectorAll(statusSelector)]
        : [...root.querySelectorAll(statusSelector)];

    if (primaryTypeCheckboxes.length === 0 && primaryStatusCheckboxes.length === 0) {
        return;
    }

    const countEl = root.querySelector('#block-results-count');
    const template = countEl?.dataset.countTemplate;
    const referenceTotal = Number.parseInt(countEl?.dataset.referenceTotal || '0', 10);

    const syncModalFromDesktop = () => {
        if (!modalRoot || !desktopRoot) {
            return;
        }

        [...desktopRoot.querySelectorAll(typeSelector)].forEach((input) => {
            const modalInput = modalRoot.querySelector(
                `input[data-filter-type="${CSS.escape(input.value)}"]`,
            );
            if (modalInput) {
                modalInput.checked = input.checked;
            }
        });

        [...desktopRoot.querySelectorAll(statusSelector)].forEach((input) => {
            const modalInput = modalRoot.querySelector(
                `input[data-filter-status="${CSS.escape(input.value)}"]`,
            );
            if (modalInput) {
                modalInput.checked = input.checked;
            }
        });
    };

    const syncDesktopFromModal = () => {
        if (!modalRoot || !desktopRoot) {
            return;
        }

        modalRoot.querySelectorAll(typeSelector).forEach((input) => {
            const desktopInput = desktopRoot.querySelector(
                `input[data-filter-type="${CSS.escape(input.value)}"]`,
            );
            if (desktopInput) {
                desktopInput.checked = input.checked;
            }
        });

        modalRoot.querySelectorAll(statusSelector).forEach((input) => {
            const desktopInput = desktopRoot.querySelector(
                `input[data-filter-status="${CSS.escape(input.value)}"]`,
            );
            if (desktopInput) {
                desktopInput.checked = input.checked;
            }
        });
    };

    const extractFeatureValues = (feature) => {
        const props = feature?.properties ?? {};
        const typeRaw = props.type;
        const statusRaw = props.status;
        const typeValue = typeof typeRaw === 'object' && typeRaw !== null
            ? String(typeRaw.value ?? '')
            : String(typeRaw ?? '');
        const statusValue = typeof statusRaw === 'object' && statusRaw !== null
            ? String(statusRaw.value ?? '')
            : String(statusRaw ?? '');

        return { typeValue, statusValue };
    };

    const countMatchingFeatures = (mapEl, checkedTypes, checkedStatuses) => {
        if (!mapEl || !Array.isArray(mapEl._allFeatures)) {
            return null;
        }

        const typeSet = checkedTypes.length > 0 ? new Set(checkedTypes) : null;
        const statusSet = checkedStatuses.length > 0 ? new Set(checkedStatuses) : null;

        if (typeSet === null && statusSet === null) {
            return mapEl._allFeatures.length;
        }

        return mapEl._allFeatures.filter((feature) => {
            const { typeValue, statusValue } = extractFeatureValues(feature);
            if (typeSet !== null && !typeSet.has(typeValue)) {
                return false;
            }
            if (statusSet !== null && !statusSet.has(statusValue)) {
                return false;
            }

            return true;
        }).length;
    };

    const dispatchFiltersChanged = (checkedTypes, checkedStatuses) => {
        const detail = {
            types: checkedTypes,
            statuses: checkedStatuses,
        };
        const mapTarget = document.getElementById('block-map');
        if (mapTarget) {
            mapTarget.dispatchEvent(new CustomEvent('filters-changed', { detail }));
        } else {
            window.dispatchEvent(new CustomEvent('filters-changed', { detail }));
        }
    };

    const applyFilters = () => {
        const checkedTypes = primaryTypeCheckboxes
            .filter((input) => input.checked)
            .map((input) => input.value);
        const checkedStatuses = primaryStatusCheckboxes
            .filter((input) => input.checked)
            .map((input) => input.value);
        const mapEl = document.getElementById('block-map');

        if (
            mapEl
            && Array.isArray(mapEl._allFeatures)
            && mapEl._allFeatures.length > 0
        ) {
            dispatchFiltersChanged(checkedTypes, checkedStatuses);
        } else if (mapEl && typeof mapEl.filterByTypes === 'function') {
            mapEl.filterByTypes(checkedTypes.length > 0 ? checkedTypes : null);
            if (typeof mapEl.filterByStatuses === 'function') {
                mapEl.filterByStatuses(checkedStatuses.length > 0 ? checkedStatuses : null);
            }
        }

        if (!countEl || !template) {
            return;
        }

        const featureCount = countMatchingFeatures(mapEl, checkedTypes, checkedStatuses);
        if (featureCount !== null) {
            const total = featureCount > 0 ? featureCount : (referenceTotal > 0 ? referenceTotal : 0);
            countEl.textContent = template.replace(':count', String(total));

            return;
        }

        const allTypesSelected =
            checkedTypes.length === primaryTypeCheckboxes.length && primaryTypeCheckboxes.length > 0;
        const noTypesSelected = checkedTypes.length === 0;
        const allStatusesSelected =
            checkedStatuses.length === primaryStatusCheckboxes.length
            && primaryStatusCheckboxes.length > 0;
        const noStatusesSelected = checkedStatuses.length === 0;

        let total = 0;
        if (
            (noTypesSelected || allTypesSelected)
            && (noStatusesSelected || allStatusesSelected)
        ) {
            total = referenceTotal > 0
                ? referenceTotal
                : primaryTypeCheckboxes.reduce(
                    (sum, input) => sum + Number.parseInt(input.dataset.count || '0', 10),
                    0,
                );
        } else {
            total = referenceTotal > 0 ? referenceTotal : 0;
        }

        countEl.textContent = template.replace(':count', String(total));
    };

    const bindCheckboxes = (inputs, onChange) => {
        inputs.forEach((input) => {
            input.addEventListener('change', onChange);
        });
    };

    bindCheckboxes(primaryTypeCheckboxes, () => {
        syncModalFromDesktop();
        applyFilters();
    });
    bindCheckboxes(primaryStatusCheckboxes, () => {
        syncModalFromDesktop();
        applyFilters();
    });

    if (modalRoot) {
        bindCheckboxes([...modalRoot.querySelectorAll(typeSelector)], () => {
            syncDesktopFromModal();
            applyFilters();
        });
        bindCheckboxes([...modalRoot.querySelectorAll(statusSelector)], () => {
            syncDesktopFromModal();
            applyFilters();
        });
    }

    window.addEventListener('filter-types-updated', applyFilters);

    const mapEl = document.getElementById('block-map');
    if (mapEl) {
        mapEl.addEventListener('geo-map-loaded', applyFilters);
    }

    const clearLink = root.querySelector('#block-clear-filters');
    if (clearLink) {
        clearLink.addEventListener('click', (event) => {
            event.preventDefault();
            [...primaryTypeCheckboxes, ...primaryStatusCheckboxes].forEach((input) => {
                input.checked = false;
            });
            modalRoot?.querySelectorAll(`${typeSelector}, ${statusSelector}`).forEach((input) => {
                input.checked = false;
            });
            applyFilters();
        });
    }
}
