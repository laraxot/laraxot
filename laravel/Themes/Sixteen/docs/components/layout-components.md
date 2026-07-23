# Layout Components - Bootstrap Italia to Tailwind

## Grid System

### Bootstrap Italia
```html
<div class="container">
  <div class="row">
    <div class="col-md-4">Colonna 1</div>
    <div class="col-md-4">Colonna 2</div>
    <div class="col-md-4">Colonna 3</div>
  </div>
</div>
```

### Tailwind CSS (Sixteen)
```html
<div class="container mx-auto px-4">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div>Colonna 1</div>
    <div>Colonna 2</div>
    <div>Colonna 3</div>
  </div>
</div>
```

## Cards

### Bootstrap Italia
```html
<div class="card">
  <div class="card-header">
    <h5 class="card-title">Titolo Card</h5>
  </div>
  <div class="card-body">
    <p class="card-text">Contenuto della card</p>
    <a href="#" class="btn btn-primary">Azione</a>
  </div>
</div>
```

### Tailwind CSS (Sixteen)
```html
<div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
    <h5 class="text-lg font-semibold text-gray-900">Titolo Card</h5>
  </div>
  <div class="px-6 py-4">
    <p class="text-gray-700 mb-4">Contenuto della card</p>
    <a href="#" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
      Azione
    </a>
  </div>
</div>
```

## Hero Sections

### Hero con Immagine di Sfondo
```html
<section class="relative bg-cover bg-center bg-no-repeat py-24" style="background-image: url('hero-bg.jpg')">
  <div class="absolute inset-0 bg-black bg-opacity-50"></div>
  <div class="relative container mx-auto px-4 text-center text-white">
    <h1 class="text-4xl md:text-6xl font-bold mb-6">
      Questo è il sito informativo di un ente pubblico italiano
    </h1>
    <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">
      Platea dictumst vestibulum rhoncus est pellentesque elit ullamcorper dignissim cras.
    </p>
    <a href="#" class="inline-flex items-center px-8 py-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
      Azione primaria
    </a>
  </div>
</section>
```

### Hero Centrato
```html
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-24">
  <div class="container mx-auto px-4 text-center">
    <h1 class="text-4xl md:text-6xl font-bold mb-6">
      Questo è il sito informativo di un ente pubblico italiano
    </h1>
    <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto opacity-90">
      Platea dictumst vestibulum rhoncus est pellentesque elit ullamcorper dignissim cras.
    </p>
    <a href="#" class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
      Azione primaria
    </a>
  </div>
</section>
```

## Modal

### Bootstrap Italia
```html
<div class="modal fade" id="exampleModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Titolo Modal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Contenuto del modal</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
        <button type="button" class="btn btn-primary">Salva</button>
      </div>
    </div>
  </div>
</div>
```

### Tailwind CSS (Sixteen) con Alpine.js
```html
<div x-data="{ open: false }" x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
  <div class="flex items-center justify-center min-h-screen px-4">
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50" @click="open = false"></div>
    
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
      
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
        <h5 class="text-lg font-semibold text-gray-900">Titolo Modal</h5>
        <button @click="open = false" class="text-gray-400 hover:text-gray-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
      
      <div class="px-6 py-4">
        <p class="text-gray-700">Contenuto del modal</p>
      </div>
      
      <div class="flex justify-end space-x-3 px-6 py-4 border-t border-gray-200">
        <button @click="open = false" class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
          Chiudi
        </button>
        <button class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
          Salva
        </button>
      </div>
    </div>
  </div>
</div>
```

## Alerts/Notifications

### Bootstrap Italia
```html
<div class="alert alert-success" role="alert">
  <strong>Successo!</strong> Operazione completata con successo.
</div>
<div class="alert alert-danger" role="alert">
  <strong>Errore!</strong> Si è verificato un errore.
</div>
```

### Tailwind CSS (Sixteen)
```html
<!-- Alert Successo -->
<div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
  <div class="flex items-center">
    <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
    </svg>
    <div>
      <p class="text-green-800">
        <strong class="font-semibold">Successo!</strong> Operazione completata con successo.
      </p>
    </div>
  </div>
</div>

<!-- Alert Errore -->
<div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
  <div class="flex items-center">
    <svg class="w-5 h-5 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
    </svg>
    <div>
      <p class="text-red-800">
        <strong class="font-semibold">Errore!</strong> Si è verificato un errore.
      </p>
    </div>
  </div>
</div>
```

## Tabs

### Bootstrap Italia
```html
<ul class="nav nav-tabs" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" data-bs-toggle="tab" href="#tab1">Tab 1</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-bs-toggle="tab" href="#tab2">Tab 2</a>
  </li>
</ul>
<div class="tab-content">
  <div class="tab-pane active" id="tab1">Contenuto Tab 1</div>
  <div class="tab-pane" id="tab2">Contenuto Tab 2</div>
</div>
```

### Tailwind CSS (Sixteen) con Alpine.js
```html
<div x-data="{ activeTab: 'tab1' }">
  <div class="border-b border-gray-200">
    <nav class="flex space-x-8">
      <button @click="activeTab = 'tab1'" :class="activeTab === 'tab1' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="py-2 px-1 border-b-2 font-medium text-sm transition-colors">
        Tab 1
      </button>
      <button @click="activeTab = 'tab2'" :class="activeTab === 'tab2' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="py-2 px-1 border-b-2 font-medium text-sm transition-colors">
        Tab 2
      </button>
    </nav>
  </div>
  
  <div class="mt-4">
    <div x-show="activeTab === 'tab1'" class="space-y-4">
      <p>Contenuto Tab 1</p>
    </div>
    <div x-show="activeTab === 'tab2'" class="space-y-4">
      <p>Contenuto Tab 2</p>
    </div>
  </div>
</div>
```

## Accordion

### Bootstrap Italia
```html
<div class="accordion" id="accordionExample">
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
        Accordion Item #1
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse show">
      <div class="accordion-body">
        Contenuto dell'accordion
      </div>
    </div>
  </div>
</div>
```

### Tailwind CSS (Sixteen) con Alpine.js
```html
<div class="border border-gray-200 rounded-lg">
  <div x-data="{ open: true }">
    <button @click="open = !open" class="flex items-center justify-between w-full px-6 py-4 text-left bg-gray-50 hover:bg-gray-100 transition-colors">
      <span class="font-medium text-gray-900">Accordion Item #1</span>
      <svg :class="open ? 'rotate-180' : ''" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
      </svg>
    </button>
    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-6 py-4 border-t border-gray-200">
      <p class="text-gray-700">Contenuto dell'accordion</p>
    </div>
  </div>
</div>
```
