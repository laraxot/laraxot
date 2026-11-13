{{--
  Callers: CMS auth.register → <x-page> → questo blocco (Folio pages/auth/register).
  Guscio e resa: <x-auth.shell> + widget Livewire dominio (DCP-290): card, campi, pulsante Google.
  Widget Gdpr RegisterWidget = dominio (consensi + create user). Nessun controller.
  Compatibilità: alias per il componente riorganizzato in auth/register.blade.php.
 --}}
@include('pub_theme::components.pages.auth.register')