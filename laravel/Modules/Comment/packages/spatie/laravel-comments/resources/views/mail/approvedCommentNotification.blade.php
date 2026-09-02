@component('mail::message')
# {{ (string) __('comments::notifications.approved_comment_mail_title', [
    'commentable_name' => $topLevelComment->commentable->commentableName(),
    'commentable_url' => $topLevelComment->commentable->commentUrl(),
    'commentator_name' => $comment->commentatorProperties()->name ?? 'anonymous',
]) }}

{{ (string) __('comments::notifications.approved_comment_mail_body', [
    'commentable_name' => $topLevelComment->commentable->commentableName(),
    'commentable_url' => $topLevelComment->commentable->commentUrl(),
    'commentator_name' => $comment->commentatorProperties()->name ?? 'anonymous',
]) }}

{!! $comment->text !!}

@component('mail::button', ['url' => $comment->commentUrl()])
{{ (string) __('comments::notifications.view_comment') }}
@endcomponent

@if($unsubscribeUrl = $commentator->unsubscribeFromCommentNotificationsUrl($comment))
<a href="{{ $unsubscribeUrl }}">Unsubscribe from receive notification about {{ $topLevelComment->commentable->commentableName() }}</a>
@endif

@endcomponent
