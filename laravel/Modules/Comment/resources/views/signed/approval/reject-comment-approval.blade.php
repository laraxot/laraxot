<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head><meta charset="utf-8"><title>{{ __('comment::notifications.reject_comment') }}</title></head>
<body>
<p>Confermi il rifiuto del commento #{{ $comment->id }}?</p>
<form method="POST">
    @csrf
    <button type="submit">{{ __('comment::notifications.reject_comment') }}</button>
</form>
</body>
</html>
