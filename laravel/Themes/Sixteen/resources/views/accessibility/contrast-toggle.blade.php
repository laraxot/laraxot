{{-- Accessibility Contrast Toggle Component (AGID-friendly)

Usage:
<x-pub_theme::accessibility.contrast-toggle />

This component toggles a high-contrast mode by adding/removing a `high-contrast` class on <html>.
Persisted via localStorage key: `agid_high_contrast`.
--}} 
<button
    type="button"
    class="inline-flex items-center gap-2 px-3 py-2 rounded border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600"
    x-data="{ on: false }"
    x-init="on = localStorage.getItem('agid_high_contrast') === '1'; document.documentElement.classList.toggle('high-contrast', on);"
    x-on:click="on = !on; document.documentElement.classList.toggle('high-contrast', on); localStorage.setItem('agid_high_contrast', on ? '1' : '0');"
    aria-pressed="false"
>
    <span class="sr-only">Attiva/disattiva contrasto elevato</span>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
        <path d="M12 2a10 10 0 100 20 10 10 0 010-20Zm1 2.07v15.86A8.001 8.001 0 0012 4c.34 0 .67.02 1 .07Z" />
    </svg>
    <span class="text-sm">Contrasto</span>
</button>

{{-- Minimal high-contrast style (can be extended in CSS global) --}}
<style>
    html.high-contrast body { background:#000 !important; color:#fff !important; }
    html.high-contrast a { color:#0ff !important; }
    html.high-contrast .btn, html.high-contrast button { background:#000 !important; color:#fff !important; border-color:#fff !important; }
</style>
