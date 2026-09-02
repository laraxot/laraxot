@component('mail::message')

{{ (string) __('comment::notifications.pending_comment_mail_body', [
    'commentable_name' => $topLevelComment->commentableName(),
    'commentator_name' => $comment->commentatorProperties()?->name ?? __('comment::txt.anonymous'),
]) }}

[{{ (string) __('comment::notifications.view_comment') }}]({{ $comment->commentUrl() }})

{!! $comment->text !!}

@component('mail::button', ['url' => $comment->approveUrl()])
    {{ (string) __('comment::notifications.approve_comment') }}
@endcomponent

@component('mail::button', ['url' => $comment->rejectUrl()])
    {{ (string) __('comment::notifications.reject_comment') }}
@endcomponent

@endcomponent
