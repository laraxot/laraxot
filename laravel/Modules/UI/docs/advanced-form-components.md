# Componenti Form Avanzati

## 📝 Input Avanzati

### Input con Icona
```html
<div class="input-group">
  <span class="input-group-text">
    <i class="fas fa-user"></i>
  </span>
  <input type="text" class="form-control" placeholder="Username">
</div>
```

### Input con Validazione
```html
<div class="form-group">
  <label for="email">Email</label>
<<<<<<< HEAD
  <input
    type="email"
    id="email"
    type="email" 
    id="email" 
=======
<<<<<<< HEAD
  <input 
    type="email" 
    id="email" 
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
  <input
    type="email"
    id="email"
<<<<<<< HEAD
    type="email" 
    id="email" 
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
  <input 
    type="email" 
    id="email" 
=======
  <input
    type="email"
    id="email"
>>>>>>> laraxot/dev
=======
  <input 
    type="email" 
    id="email" 
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
    class="form-control is-invalid"
    aria-describedby="email-error"
  >
  <div id="email-error" class="invalid-feedback">
    Inserisci un indirizzo email valido
  </div>
</div>
```

### Input con Autocompletamento
```html
<div class="form-group">
  <label for="search">Cerca</label>
<<<<<<< HEAD
  <input
    type="text"
    id="search"
    type="text" 
    id="search" 
=======
<<<<<<< HEAD
  <input 
    type="text" 
    id="search" 
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
  <input
    type="text"
    id="search"
<<<<<<< HEAD
    type="text" 
    id="search" 
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
  <input 
    type="text" 
    id="search" 
=======
  <input
    type="text"
    id="search"
>>>>>>> laraxot/dev
=======
  <input 
    type="text" 
    id="search" 
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
    class="form-control"
    list="suggestions"
    autocomplete="off"
  >
  <datalist id="suggestions">
    <option value="Suggerimento 1">
    <option value="Suggerimento 2">
  </datalist>
</div>
```

## 📅 Selezione Data e Ora

### DatePicker
```html
<div class="form-group">
  <label for="date">Data</label>
  <div class="input-group datepicker">
<<<<<<< HEAD
    <input
      type="text"
      id="date"
      type="text" 
      id="date" 
=======
<<<<<<< HEAD
    <input 
      type="text" 
      id="date" 
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
    <input
      type="text"
      id="date"
<<<<<<< HEAD
      type="text" 
      id="date" 
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
    <input 
      type="text" 
      id="date" 
=======
    <input
      type="text"
      id="date"
>>>>>>> laraxot/dev
=======
    <input 
      type="text" 
      id="date" 
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
      class="form-control"
      data-date-format="dd/mm/yyyy"
    >
    <span class="input-group-text">
      <i class="fas fa-calendar"></i>
    </span>
  </div>
</div>
```

### TimePicker
```html
<div class="form-group">
  <label for="time">Ora</label>
  <div class="input-group timepicker">
<<<<<<< HEAD
    <input
      type="text"
      id="time"
      type="text" 
      id="time" 
=======
<<<<<<< HEAD
    <input 
      type="text" 
      id="time" 
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
    <input
      type="text"
      id="time"
<<<<<<< HEAD
      type="text" 
      id="time" 
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
    <input 
      type="text" 
      id="time" 
=======
    <input
      type="text"
      id="time"
>>>>>>> laraxot/dev
=======
    <input 
      type="text" 
      id="time" 
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
      class="form-control"
      data-time-format="HH:mm"
    >
    <span class="input-group-text">
      <i class="fas fa-clock"></i>
    </span>
  </div>
</div>
```

## 📊 Input Numerici

### Range Slider
```html
<div class="form-group">
  <label for="range">Range</label>
<<<<<<< HEAD
  <input
    type="range"
    id="range"
    type="range" 
    id="range" 
=======
<<<<<<< HEAD
  <input 
    type="range" 
    id="range" 
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
  <input
    type="range"
    id="range"
<<<<<<< HEAD
    type="range" 
    id="range" 
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
  <input 
    type="range" 
    id="range" 
=======
  <input
    type="range"
    id="range"
>>>>>>> laraxot/dev
=======
  <input 
    type="range" 
    id="range" 
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
    class="form-range"
    min="0"
    max="100"
    step="1"
  >
  <div class="range-value">50</div>
</div>
```

### Input con Step
```html
<div class="form-group">
  <label for="quantity">Quantità</label>
  <div class="input-group">
    <button class="btn btn-outline-secondary" type="button">-</button>
<<<<<<< HEAD
    <input
      type="number"
      id="quantity"
      type="number" 
      id="quantity" 
=======
<<<<<<< HEAD
    <input 
      type="number" 
      id="quantity" 
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
    <input
      type="number"
      id="quantity"
<<<<<<< HEAD
      type="number" 
      id="quantity" 
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
    <input 
      type="number" 
      id="quantity" 
=======
    <input
      type="number"
      id="quantity"
>>>>>>> laraxot/dev
=======
    <input 
      type="number" 
      id="quantity" 
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
      class="form-control text-center"
      min="0"
      max="100"
      step="1"
      value="1"
    >
    <button class="btn btn-outline-secondary" type="button">+</button>
  </div>
</div>
```

## 📎 Upload File

### Drag & Drop
```html
<div class="upload-area">
  <input type="file" id="file" class="d-none">
  <label for="file" class="upload-label">
    <i class="fas fa-cloud-upload-alt"></i>
    <span>Trascina i file qui o clicca per selezionare</span>
  </label>
  <div class="upload-preview"></div>
</div>
```

### Preview Immagini
```html
<div class="image-upload">
  <input type="file" id="image" accept="image/*" class="d-none">
  <label for="image" class="image-preview">
    <img src="placeholder.jpg" alt="Preview">
    <div class="image-overlay">
      <i class="fas fa-camera"></i>
      <span>Cambia immagine</span>
    </div>
  </label>
</div>
```

## 🎨 Stili e Comportamenti

### Focus States
```scss
.form-control {
  &:focus {
    border-color: $primary;
    box-shadow: 0 0 0 0.2rem rgba($primary, 0.25);
  }
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
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
  
  &.is-invalid {
    border-color: $danger;
    
<<<<<<< HEAD
=======
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
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev

  &.is-invalid {
    border-color: $danger;

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
    &:focus {
      box-shadow: 0 0 0 0.2rem rgba($danger, 0.25);
    }
  }
}
```

### Animazioni
```scss
.input-group {
  transition: all 0.3s ease;
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
  &:focus-within {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
}
```

## 🔗 Collegamenti
- [Componenti Base](./base-components.md)
- [Feedback](./feedback-components.md)
- [Accessibilità](./standards/accessibility.md)
<<<<<<< HEAD
=======
<<<<<<< HEAD
- [Performance](./standards/performance.md) 
=======
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
- [Performance](./standards/performance.md) 
=======
=======
=======
<<<<<<< HEAD
<<<<<<< HEAD
- [Performance](./standards/performance.md) 
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
- [Performance](./standards/performance.md)
# Componenti Form Avanzati
## 📝 Input Avanzati
### Input con Icona
```html
<div class="input-group">
  <span class="input-group-text">
    <i class="fas fa-user"></i>
  </span>
  <input type="text" class="form-control" placeholder="Username">
</div>
```
### Input con Validazione
<div class="form-group">
  <label for="email">Email</label>
  <input
    type="email"
    id="email"
    class="form-control is-invalid"
    aria-describedby="email-error"
  >
  <div id="email-error" class="invalid-feedback">
    Inserisci un indirizzo email valido
  </div>
### Input con Autocompletamento
  <label for="search">Cerca</label>
    type="text"
    id="search"
    class="form-control"
    list="suggestions"
    autocomplete="off"
  <datalist id="suggestions">
    <option value="Suggerimento 1">
    <option value="Suggerimento 2">
  </datalist>
## 📅 Selezione Data e Ora
### DatePicker
  <label for="date">Data</label>
  <div class="input-group datepicker">
    <input
      type="text"
      id="date"
      class="form-control"
      data-date-format="dd/mm/yyyy"
    >
    <span class="input-group-text">
      <i class="fas fa-calendar"></i>
    </span>
### TimePicker
  <label for="time">Ora</label>
  <div class="input-group timepicker">
      id="time"
      data-time-format="HH:mm"
      <i class="fas fa-clock"></i>
## 📊 Input Numerici
### Range Slider
  <label for="range">Range</label>
    type="range"
    id="range"
    class="form-range"
    min="0"
    max="100"
    step="1"
  <div class="range-value">50</div>
### Input con Step
  <label for="quantity">Quantità</label>
  <div class="input-group">
    <button class="btn btn-outline-secondary" type="button">-</button>
      type="number"
      id="quantity"
      class="form-control text-center"
      min="0"
      max="100"
      step="1"
      value="1"
    <button class="btn btn-outline-secondary" type="button">+</button>
## 📎 Upload File
### Drag & Drop
<div class="upload-area">
  <input type="file" id="file" class="d-none">
  <label for="file" class="upload-label">
    <i class="fas fa-cloud-upload-alt"></i>
    <span>Trascina i file qui o clicca per selezionare</span>
  </label>
  <div class="upload-preview"></div>
### Preview Immagini
<div class="image-upload">
  <input type="file" id="image" accept="image/*" class="d-none">
  <label for="image" class="image-preview">
    <img src="placeholder.jpg" alt="Preview">
    <div class="image-overlay">
      <i class="fas fa-camera"></i>
      <span>Cambia immagine</span>
    </div>
## 🎨 Stili e Comportamenti
### Focus States
```scss
.form-control {
  &:focus {
    border-color: $primary;
    box-shadow: 0 0 0 0.2rem rgba($primary, 0.25);
  }
  &.is-invalid {
    border-color: $danger;
    &:focus {
      box-shadow: 0 0 0 0.2rem rgba($danger, 0.25);
    }
}
### Animazioni
.input-group {
  transition: all 0.3s ease;
  &:focus-within {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
## 🔗 Collegamenti
- [Componenti Base](./base-components.md)
- [Feedback](./feedback-components.md)
- [Accessibilità](./standards/accessibility.md)
<<<<<<< HEAD

```
=======
<<<<<<< HEAD

```
=======
- [Performance](./standards/performance.md)
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
=======
- [Performance](./standards/performance.md) 
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
