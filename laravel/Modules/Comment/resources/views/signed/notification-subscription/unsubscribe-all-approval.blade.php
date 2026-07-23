<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<body>
<p>Confermi disiscrizione da tutte le notifiche commenti?</p>
<form method="POST">
    @csrf
    <button type="submit">Conferma</button>
</form>
</body>
</html>
