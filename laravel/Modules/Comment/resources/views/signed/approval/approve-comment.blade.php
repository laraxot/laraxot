<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head><meta charset="utf-8"><title>{{ __('comment::notifications.approve_comment') }}</title></head>
<body>
<p>Commento #{{ $comment->id }} approvato.</p>
</body>
</html>
