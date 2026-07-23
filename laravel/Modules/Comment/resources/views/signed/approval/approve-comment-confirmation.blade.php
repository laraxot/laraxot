<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head><meta charset="utf-8"><title>{{ __('comment::notifications.approve_comment') }}</title></head>
<body>
<p>Confermi l'approvazione del commento #{{ $comment->id }}?</p>
<form method="POST">
    @csrf
    <button type="submit">{{ __('comment::notifications.approve_comment') }}</button>
</form>
</body>
</html>
