@props(['date'])

<time datetime="{{ $date->format('Y-m-d H:i:s') }}" class="comments-date">
    {{ $date->diffInMinutes() < 1 ? (string) __('comments::comments.just_now') : $date->diffForHumans() }}
</time>
