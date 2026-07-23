Motivazione

Alcune view usano helper di Laravel\Folio (middleware, name, title, render, ecc.). Questi helpers sono funzioni globali nel namespace Laravel\Folio e devono essere importate esplicitamente nelle view quando il compilatore Blade e gli interceptor di Folio iniettano chiamate a queste funzioni.

Regola rapida (second brain):
- In cima alle view che fanno uso di metadati inline (slot title, name, ecc), aggiungere:
  <?php use function Laravel\\Folio\\{middleware, name, title}; ?>
- Preferire l'utilizzo di componenti/slot semantici (<x-slot name="title">) ma mantenere l'import per compatibilità con Folio interceptors.

Esempio:

<?php use function Laravel\\Folio\\{middleware, name, title}; ?>
<x-layouts.app>
  <x-slot name="title">{{ __('user::auth.register.page.meta_title.label') }}</x-slot>
  ...
</x-layouts.app>

Motivo tecnico:
- InlineMetadataInterceptor può generare chiamate a title() durante il rendering; senza import la chiamata risulterebbe in "Call to undefined function Laravel\Folio\title()".

Azione consigliata:
- Inserire l'import nelle view critiche (auth, pages dinamiche) e aggiungere una regola CI che verifica la presenza dell'import nelle view che utilizzano x-slot title.
