{{--
  Callers: CMS auth.login → <x-page> → questo blocco (Folio pages/auth/login).
  Guscio e resa: <x-auth.shell> + <x-auth.assets> (condivisi con registrazione e recupero password, DCP-290).
  Widget User LoginWidget = dominio (credenziali). Nessun controller.
  Alias per il componente riorganizzato: auth/login.blade.php.
 --}}
@include('pub_theme::components.pages.auth.login')