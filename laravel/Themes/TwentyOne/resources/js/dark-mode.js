// Dark Mode Toggle - TwentyOne Theme
// Adds dark mode toggle functionality with localStorage persistence

const initDarkMode = () => {
    const toggle = document.getElementById('dark-mode-toggle');
    const html = document.documentElement;

    if (!toggle) {
        return;
    }

    // Check localStorage or system preference
    const getPreferredTheme = () => {
        const stored = localStorage.getItem('theme');
        if (stored) {
            return stored;
        }
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    };

    // Apply theme
    const applyTheme = (theme) => {
        const isDark = theme === 'dark';
        
        if (isDark) {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            toggle.setAttribute('aria-checked', 'true');
        } else {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            toggle.setAttribute('aria-checked', 'false');
        }

        // Update tooltip text
        const tooltip = toggle.nextElementSibling;
        if (tooltip && tooltip.tagName === 'SPAN') {
            tooltip.textContent = isDark ? 'Tema Chiaro' : 'Tema Scuro';
        }
    };

    // Initialize
    applyTheme(getPreferredTheme());

    // Toggle click
    toggle.addEventListener('click', () => {
        const isDark = html.classList.contains('dark');
        applyTheme(isDark ? 'light' : 'dark');

        // Animate icon transition (handled by CSS classes)
    });

    // Keyboard shortcut (D)
    document.addEventListener('keydown', (e) => {
        if (e.key === 'd' && !e.ctrlKey && !e.metaKey && !e.altKey) {
            toggle.click();
        }
    });

    // Listen for system changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (!localStorage.getItem('theme')) {
            applyTheme(e.matches ? 'dark' : 'light');
        }
    });
};

// Export for use in app.js
export { initDarkMode };
