@component('mail::message')
# {{ (string) __('comment::notifications.approved_comment_mail_title', [
    'commentable_name' => $topLevelComment->commentableName(),
]) }}

{{ (string) __('comment::notifications.approved_comment_mail_body', [
    'commentable_name' => $topLevelComment->commentableName(),
    'commentator_name' => $comment->commentatorProperties()?->name ?? __('comment::txt.anonymous'),
]) }}

{!! $comment->text !!}

@component('mail::button', ['url' => $comment->commentUrl()])
{{ (string) __('comment::notifications.view_comment') }}
@endcomponent
@endcomponent
