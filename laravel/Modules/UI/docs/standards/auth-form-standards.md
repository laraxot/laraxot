<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
---
title: "Standard Form di Autenticazione"
type: rule
tags: [auth, form, standards]
created: 2026-07-14
updated: 2026-07-14
qmd: "auth-form-standards standard form di autenticazione"
issues: ["https://github.com/provtv/<nome repository>/issues/124"]
discussions: ["https://github.com/provtv/<nome repository>/discussions/1"]
related:
  - "./accessibility.md"
  - "./auth-form-standards-1.md"
  - "./form-standards-1.md"
  - "./form-standards.md"
  - "./performance.md"
  - "./ui-standards.md"
---
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======

<<<<<<< HEAD
>>>>>>> laraxot/dev
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
# Standard Form di Autenticazione

## Principi di Design

### Layout
- I form di autenticazione devono essere centrati sia orizzontalmente che verticalmente
- Utilizzare una larghezza massima appropriata per garantire leggibilità
- Mantenere una gerarchia visiva chiara con spaziatura consistente

### Container
```html
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-md">
    <!-- Logo o intestazione -->
  </div>

  <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
    <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
      <!-- Form -->
    </div>
  </div>
</div>
```

### Intestazione
```html
<div class="sm:mx-auto sm:w-full sm:max-w-md">
  <img class="mx-auto h-12 w-auto" src="logo.svg" alt="Logo">
  <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
    Titolo del Form
  </h2>
  <p class="mt-2 text-center text-sm text-gray-600">
    Sottotitolo o descrizione
  </p>
</div>
```

### Form
```html
<form class="space-y-6">
  <div>
    <label class="block text-sm font-medium text-gray-700">
      Email
    </label>
    <div class="mt-1">
<<<<<<< HEAD
      <input
        type="email"
        type="email" 
=======
<<<<<<< HEAD
      <input 
        type="email" 
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
      <input
        type="email"
<<<<<<< HEAD
        type="email" 
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
      <input 
        type="email" 
=======
      <input
        type="email"
>>>>>>> laraxot/dev
=======
      <input 
        type="email" 
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
      >
    </div>
  </div>
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev

  <!-- Altri campi -->

  <div>
    <button
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
  
  <!-- Altri campi -->
  
  <div>
    <button 
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev

  <!-- Altri campi -->
  
  <div>
    <button
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
      type="submit"
      class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
    >
      Invia
    </button>
  </div>
</form>
```

## Responsive Design

### Mobile
- Form a larghezza piena
- Padding ridotto
- Stack verticale per tutti gli elementi

```scss
// Mobile (default)
.auth-container {
  @apply px-4 py-8;
}

.auth-form {
  @apply w-full;
}
```

### Tablet e Desktop
- Form centrato con larghezza massima
- Padding aumentato
- Possibile layout a due colonne per alcuni elementi

```scss
// Tablet (sm)
@screen sm {
  .auth-container {
    @apply px-6 py-12;
  }

  .auth-form {
    @apply max-w-md mx-auto;
  }
}

// Desktop (lg)
@screen lg {
  .auth-container {
    @apply px-8;
  }
}
```

## Elementi Visivi

### Ombreggiature
```scss
.auth-card {
  @apply shadow-sm;
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
  
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======

=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
  
=======

>>>>>>> laraxot/dev
=======
  
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
  @screen sm {
    @apply shadow-md;
  }
}
```

### Bordi e Arrotondamenti
```scss
.auth-card {
  @apply rounded-lg;
}

.auth-input {
  @apply rounded-md;
}

.auth-button {
  @apply rounded-md;
}
```

### Spaziatura
```scss
.auth-section {
  @apply space-y-6;
}

.auth-field {
  @apply space-y-1;
}

.auth-actions {
  @apply mt-6;
}
```

## Accessibilità

### Focus Management
```scss
.auth-input:focus {
  @apply outline-none ring-2 ring-primary-500 border-primary-500;
}

.auth-button:focus {
  @apply outline-none ring-2 ring-offset-2 ring-primary-500;
}
```

### ARIA Labels
```html
<div class="auth-field">
  <label id="email-label">Email</label>
<<<<<<< HEAD
  <input
=======
<<<<<<< HEAD
  <input 
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
  <input
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
  <input 
=======
  <input
>>>>>>> laraxot/dev
=======
  <input 
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
    type="email"
    aria-labelledby="email-label"
    aria-required="true"
  >
</div>
```

## Best Practices

1. **Centratura e Allineamento**
   - Form sempre centrato nella viewport
   - Elementi interni allineati consistentemente
   - Larghezza massima appropriata per la leggibilità

2. **Gerarchia Visiva**
   - Logo/brand in alto
   - Titolo chiaro e descrittivo
   - Campi form ben spaziati
   - Call to action prominente

3. **Feedback Utente**
   - Validazione in tempo reale
   - Messaggi di errore chiari
   - Indicatori di stato (loading, success, error)
   - Focus states visibili

4. **Responsive Design**
   - Layout fluido su tutti i dispositivi
   - Spaziatura adattiva
   - Touch targets appropriati su mobile

5. **Performance**
   - Caricamento ottimizzato
   - Transizioni fluide
   - Gestione efficiente degli stati

6. **Sicurezza**
   - CSRF protection
   - Rate limiting
   - Validazione server-side
   - Sanitizzazione input

## Collegamenti
<<<<<<< HEAD
- [Form Standards](./form_standards.md)
=======
<<<<<<< HEAD
- [Form Standards](./form_standards.md)
=======
<<<<<<< HEAD
- [Form Standards](./form_standards.md)
- [UI Standards](./ui-standards.md)
- [Accessibility](./accessibility.md) 
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
- [Form Standards](./form_standards.md)
- [UI Standards](./ui-standards.md)
- [Accessibility](./accessibility.md) 
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
- [Form Standards](./form-standards-1.md)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
- [UI Standards](./ui-standards.md)
- [Accessibility](./accessibility.md)
- [Form Standards](./form-standards-1.md)
# Standard Form di Autenticazione
## Principi di Design
### Layout
- I form di autenticazione devono essere centrati sia orizzontalmente che verticalmente
- Utilizzare una larghezza massima appropriata per garantire leggibilità
- Mantenere una gerarchia visiva chiara con spaziatura consistente
### Container
```html
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-md">
    <!-- Logo o intestazione -->
  </div>
  <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
    <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
      <!-- Form -->
    </div>
</div>
```
### Intestazione
<div class="sm:mx-auto sm:w-full sm:max-w-md">
  <img class="mx-auto h-12 w-auto" src="logo.svg" alt="Logo">
  <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
    Titolo del Form
  </h2>
  <p class="mt-2 text-center text-sm text-gray-600">
    Sottotitolo o descrizione
  </p>
### Form
<form class="space-y-6">
  <div>
    <label class="block text-sm font-medium text-gray-700">
      Email
    </label>
    <div class="mt-1">
      <input
        type="email"
        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
      >
  <!-- Altri campi -->
    <button
      type="submit"
      class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
    >
      Invia
    </button>
</form>
## Responsive Design
### Mobile
- Form a larghezza piena
- Padding ridotto
- Stack verticale per tutti gli elementi
```scss
// Mobile (default)
.auth-container {
  @apply px-4 py-8;
}
.auth-form {
  @apply w-full;
### Tablet e Desktop
- Form centrato con larghezza massima
- Padding aumentato
- Possibile layout a due colonne per alcuni elementi
// Tablet (sm)
@screen sm {
  .auth-container {
    @apply px-6 py-12;
  }
  .auth-form {
    @apply max-w-md mx-auto;
// Desktop (lg)
@screen lg {
    @apply px-8;
## Elementi Visivi
### Ombreggiature
.auth-card {
  @apply shadow-sm;
  @screen sm {
    @apply shadow-md;
### Bordi e Arrotondamenti
  @apply rounded-lg;
.auth-input {
  @apply rounded-md;
.auth-button {
### Spaziatura
.auth-section {
  @apply space-y-6;
.auth-field {
  @apply space-y-1;
.auth-actions {
  @apply mt-6;
## Accessibilità
### Focus Management
.auth-input:focus {
  @apply outline-none ring-2 ring-primary-500 border-primary-500;
.auth-button:focus {
  @apply outline-none ring-2 ring-offset-2 ring-primary-500;
### ARIA Labels
<div class="auth-field">
  <label id="email-label">Email</label>
  <input
    type="email"
    aria-labelledby="email-label"
    aria-required="true"
  >
## Best Practices
1. **Centratura e Allineamento**
   - Form sempre centrato nella viewport
   - Elementi interni allineati consistentemente
   - Larghezza massima appropriata per la leggibilità
2. **Gerarchia Visiva**
   - Logo/brand in alto
   - Titolo chiaro e descrittivo
   - Campi form ben spaziati
   - Call to action prominente
3. **Feedback Utente**
   - Validazione in tempo reale
   - Messaggi di errore chiari
   - Indicatori di stato (loading, success, error)
   - Focus states visibili
4. **Responsive Design**
   - Layout fluido su tutti i dispositivi
   - Spaziatura adattiva
   - Touch targets appropriati su mobile
5. **Performance**
   - Caricamento ottimizzato
   - Transizioni fluide
   - Gestione efficiente degli stati
6. **Sicurezza**
   - CSRF protection
   - Rate limiting
   - Validazione server-side
   - Sanitizzazione input
## Collegamenti
- [UI Standards](./ui-standards.md)
<<<<<<< HEAD
- [Accessibility](./accessibility.md) 

```
=======
<<<<<<< HEAD
- [Accessibility](./accessibility.md) 

```
=======
- [Accessibility](./accessibility.md)
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
=======
- [Form Standards](./form_standards.md)
- [UI Standards](./ui-standards.md)
- [Accessibility](./accessibility.md) 
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
