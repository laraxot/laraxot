# Pattern Route - Tema Sixteen

## Problema Identificato

L'utilizzo di route dirette come `route('privacy')` e `route('accessibility')` è **ERRATO** nel sistema Laraxot.

## Analisi del Problema

### ❌ Utilizzo Errato
```blade
<a href="{{ route('privacy') }}" class="text-blue-600 hover:text-blue-800 underline">
    Informativa sulla privacy
</a>

<a href="{{ route('accessibility') }}" class="text-blue-600 hover:text-blue-800 underline">
    Dichiarazione di accessibilità
</a>
```

### Problemi Identificati
1. **Route non esistenti**: `privacy` e `accessibility` non sono route dirette
2. **Pattern errato**: Il sistema usa route dinamiche basate su slug
3. **Inconsistenza**: Alcuni file usano route dirette, altri usano il pattern corretto

## Soluzione Corretta

### ✅ Pattern Corretto (IMPLEMENTATO)
```blade
<a href="{{ route('page_slug.view', ['slug' => 'privacy']) }}" class="text-blue-600 hover:text-blue-800 underline">
    Informativa sulla privacy
</a>

<a href="{{ route('page_slug.view', ['slug' => 'accessibility']) }}" class="text-blue-600 hover:text-blue-800 underline">
    Dichiarazione di accessibilità
</a>
```

## Struttura Route del Sistema

### Route Dinamiche per Pagine
Il sistema Laraxot utilizza route dinamiche per le pagine:

```php
// File: resources/views/pages/[container0]/[slug0]/index.blade.php
name('page_slug.view');

render(function (View $view, string $slug) {
    $page = Page::firstWhere(['slug' => $slug]);
    return $view->with('page', $page);
});
```

### Pattern Route Corretto
```blade
// ✅ CORRETTO
route('page_slug.view', ['slug' => 'nome-pagina'])

// ❌ ERRATO
route('nome-pagina')
```

## Pagine Standard del Sistema

### Pagine Obbligatorie
```blade
// Privacy Policy
route('page_slug.view', ['slug' => 'privacy'])

// Accessibility Declaration
route('page_slug.view', ['slug' => 'accessibility'])

// Terms of Service
route('page_slug.view', ['slug' => 'terms'])

// Help/Support
route('page_slug.view', ['slug' => 'help'])

// Legal Notes
route('page_slug.view', ['slug' => 'legal'])
```

### Pagine Opzionali
```blade
// About Us
route('page_slug.view', ['slug' => 'about'])

// Contact
route('page_slug.view', ['slug' => 'contact'])

// FAQ
route('page_slug.view', ['slug' => 'faq'])
```

## Implementazione nel Login

### ✅ Pattern Corretto per Login
```blade
<!-- AGID Compliance Information -->
<div class="mt-8 text-center text-sm text-gray-600 max-w-md mx-auto">
    <p class="mb-2">
        Questo servizio è conforme alle 
        <a href="https://www.agid.gov.it/" 
           target="_blank" 
           rel="noopener noreferrer"
           class="text-blue-600 hover:text-blue-800 underline">
            linee guida AGID
        </a>
    </p>
    <p class="mb-4">
        Per informazioni sulla privacy consulta la 
        <a href="{{ route('page_slug.view', ['slug' => 'privacy']) }}" 
           class="text-blue-600 hover:text-blue-800 underline">
            informativa sulla privacy
        </a>
    </p>
    
    <!-- Accessibility Information -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <p class="mb-2">
            <strong>Accessibilità:</strong> Questo servizio è accessibile secondo le linee guida WCAG 2.1 AA.
            <a href="{{ route('page_slug.view', ['slug' => 'accessibility']) }}" 
               class="text-blue-600 hover:text-blue-800 underline">
                Dichiarazione di accessibilità
            </a>
        </p>
        <p class="text-xs">
            <strong>Navigazione da tastiera:</strong> Usa Tab per navigare tra i campi e Invio per inviare il modulo.
        </p>
    </div>
</div>
```

## Footer Implementation

### ✅ Pattern Corretto per Footer
```blade
<!-- AGID Institutional Footer -->
<footer role="contentinfo" class="mt-12 bg-gray-900 text-white py-8">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- ... branding ... -->
            
            <div>
                <h4 class="text-lg font-semibold mb-4">Link utili</h4>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="{{ route('page_slug.view', ['slug' => 'privacy']) }}" 
                           class="opacity-80 hover:opacity-100 transition-opacity">
                            Privacy
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('page_slug.view', ['slug' => 'legal']) }}" 
                           class="opacity-80 hover:opacity-100 transition-opacity">
                            Note legali
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('page_slug.view', ['slug' => 'accessibility']) }}" 
                           class="opacity-80 hover:opacity-100 transition-opacity">
                            Dichiarazione di accessibilità
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('page_slug.view', ['slug' => 'help']) }}" 
                           class="opacity-80 hover:opacity-100 transition-opacity">
                            Assistenza
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="mt-8 pt-8 border-t border-gray-700 text-center text-sm opacity-80">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tutti i diritti riservati.</p>
        </div>
    </div>
</footer>
```

## Best Practices

### ✅ DO
- Utilizzare sempre `route('page_slug.view', ['slug' => 'nome-pagina'])`
- Verificare che le pagine esistano nel database CMS
- Utilizzare slug consistenti e standardizzati
- Documentare le pagine richieste

### ❌ DON'T
- Utilizzare route dirette come `route('privacy')`
- Hardcodare URL invece di usare route
- Assumere che le pagine esistano senza verificare
- Utilizzare slug non standardizzati

## Verifica Pagine Esistenti

### Controllo Database
```php
// Verificare che le pagine esistano
$pages = Page::whereIn('slug', ['privacy', 'accessibility', 'legal', 'help'])->get();

foreach ($pages as $page) {
    echo "Pagina {$page->slug} trovata\n";
}
```

### Pagine Richieste per AGID Compliance
1. **privacy** - Informativa sulla privacy
2. **accessibility** - Dichiarazione di accessibilità
3. **legal** - Note legali
4. **help** - Assistenza e supporto

## Collegamenti

- [Layout Usage Patterns](layout-usage-patterns.md)
- [AGID Login Implementation](agid-login-implementation.md)
- [Route Contract](./page-directory-structure.md)
- [CMS Module](../../../laravel/Modules/Cms/)

