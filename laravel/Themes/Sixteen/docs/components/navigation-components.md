# Navigation Components - Bootstrap Italia to Tailwind

## Header Navigation

### Bootstrap Italia
```html
<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container">
    <a class="navbar-brand" href="#">
      <img src="logo.svg" alt="Logo">
      Ente Pubblico
    </a>
    <button class="navbar-toggler" type="button">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-nav">
      <a class="nav-link active" href="#">Home</a>
      <a class="nav-link" href="#">Servizi</a>
      <a class="nav-link" href="#">Contatti</a>
    </div>
  </div>
</nav>
```

### Tailwind CSS (Sixteen)
```html
<nav class="bg-white shadow-sm border-b border-gray-200">
  <div class="container mx-auto px-4">
    <div class="flex items-center justify-between h-16">
      <div class="flex items-center space-x-4">
        <img src="logo.svg" alt="Logo" class="h-8 w-auto">
        <span class="text-xl font-bold text-gray-900">Ente Pubblico</span>
      </div>
      
      <!-- Desktop Menu -->
      <div class="hidden md:flex space-x-8">
        <a href="#" class="text-blue-600 font-medium border-b-2 border-blue-600 pb-1">Home</a>
        <a href="#" class="text-gray-700 hover:text-blue-600 transition-colors">Servizi</a>
        <a href="#" class="text-gray-700 hover:text-blue-600 transition-colors">Contatti</a>
      </div>
      
      <!-- Mobile Menu Button -->
      <button class="md:hidden p-2 rounded-lg hover:bg-gray-100" x-data x-on:click="$dispatch('toggle-mobile-menu')">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
      </button>
    </div>
  </div>
</nav>
```

## Breadcrumb

### Bootstrap Italia
```html
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="#">Servizi</a></li>
    <li class="breadcrumb-item active">Pagina corrente</li>
  </ol>
</nav>
```

### Tailwind CSS (Sixteen)
```html
<nav aria-label="breadcrumb" class="py-4">
  <ol class="flex items-center space-x-2 text-sm">
    <li>
      <a href="#" class="text-blue-600 hover:text-blue-800 transition-colors">Home</a>
    </li>
    <li class="text-gray-400">/</li>
    <li>
      <a href="#" class="text-blue-600 hover:text-blue-800 transition-colors">Servizi</a>
    </li>
    <li class="text-gray-400">/</li>
    <li class="text-gray-700 font-medium">Pagina corrente</li>
  </ol>
</nav>
```

## Sidebar Navigation

### Bootstrap Italia
```html
<div class="sidebar">
  <nav class="nav flex-column">
    <a class="nav-link active" href="#">Dashboard</a>
    <a class="nav-link" href="#">Utenti</a>
    <a class="nav-link" href="#">Impostazioni</a>
  </nav>
</div>
```

### Tailwind CSS (Sixteen)
```html
<aside class="w-64 bg-white shadow-sm border-r border-gray-200 h-full">
  <nav class="p-4 space-y-2">
    <a href="#" class="flex items-center px-4 py-3 text-blue-600 bg-blue-50 rounded-lg font-medium">
      <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
      </svg>
      Dashboard
    </a>
    <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
      <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
      </svg>
      Utenti
    </a>
    <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
      <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
      </svg>
      Impostazioni
    </a>
  </nav>
</aside>
```

## Footer

### Bootstrap Italia
```html
<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <h5>Ente Pubblico</h5>
        <p>Descrizione dell'ente</p>
      </div>
      <div class="col-md-6">
        <h5>Link utili</h5>
        <ul class="list-unstyled">
          <li><a href="#">Privacy</a></li>
          <li><a href="#">Note legali</a></li>
        </ul>
      </div>
    </div>
  </div>
</footer>
```

### Tailwind CSS (Sixteen)
```html
<footer class="bg-gray-900 text-white py-12">
  <div class="container mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <div>
        <h5 class="text-xl font-bold mb-4">Ente Pubblico</h5>
        <p class="text-gray-300 leading-relaxed">Descrizione dell'ente</p>
      </div>
      <div>
        <h5 class="text-xl font-bold mb-4">Link utili</h5>
        <ul class="space-y-2">
          <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Privacy</a></li>
          <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Note legali</a></li>
        </ul>
      </div>
    </div>
  </div>
</footer>
```
