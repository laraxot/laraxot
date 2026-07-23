/**
 * Dark mode — storage key must stay in sync with inline boot snippet in
 * layouts/main.blade.php (head), which runs before Vite bundles to avoid flash.
 */
export const DARK_MODE_STORAGE_KEY = 'dark_mode';

export function applyDarkClassFromStorage() {
    if (typeof Storage === 'undefined') {
        return;
    }
    if (localStorage.getItem(DARK_MODE_STORAGE_KEY) === 'true') {
        document.documentElement.classList.add('dark');
    }
}

export function toggleDarkMode() {
    const html = document.documentElement;
    const isDark = html.classList.contains('dark');
    if (isDark) {
        html.classList.remove('dark');
        localStorage.setItem(DARK_MODE_STORAGE_KEY, 'false');
    } else {
        html.classList.add('dark');
        localStorage.setItem(DARK_MODE_STORAGE_KEY, 'true');
    }
}

/**
 * Bind #darkModeToggle if present; safe to call after Livewire swaps the header.
 */
export function initDarkModeToggle() {
    const darkModeToggle = document.getElementById('darkModeToggle');
    if (!darkModeToggle || darkModeToggle._sixteenDarkBound) {
        return;
    }
    darkModeToggle._sixteenDarkBound = true;
    darkModeToggle.addEventListener('click', () => toggleDarkMode());
}
