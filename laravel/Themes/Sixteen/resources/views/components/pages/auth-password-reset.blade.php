{{--
  Callers: Modules/User pages/auth/password/reset.blade.php (route password.request).
  Guscio e resa: <x-auth.shell> come il login (DCP-290). Testi da `user::password_recovery.*` in it, en, de, es.
  Compatibilità: alias per il componente riorganizzato in auth/password-reset.blade.php.
--}}
@include('pub_theme::components.pages.auth.password-reset')