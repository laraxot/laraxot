<a {{ $attributes->merge(['href' => $href]) }}>{{ $slot->isEmpty() ? __('comment::txt.log-in-for-comment') : $slot }}</a>
