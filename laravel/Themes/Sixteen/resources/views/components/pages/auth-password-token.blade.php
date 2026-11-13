{{--
  Callers: Modules/User pages/auth/password/[token].blade.php (route password.reset).
  Guscio e resa: <x-auth.shell> come il login (DCP-290). `$token` e `$email` arrivano dalla pagina e vanno al widget.
  Compatibilità: alias per il componente riorganizzato in auth/password-token.blade.php.
--}}
@include('pub_theme::components.pages.auth.password-token')