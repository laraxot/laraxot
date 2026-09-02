@props([
    'title'=>'no-title',
    'sub_title' => null,
])
<div class="mb-10 md:mb-16">
    <h2 class="mb-4 text-center text-2xl font-bold text-gray-800 md:mb-6 lg:text-3xl">{{ $title ?? 'no-title' }}</h2>
    @if($sub_title)
      <p class="mx-auto max-w-screen-md text-center text-gray-500 md:text-lg">
        {{ $sub_title }}
      </p>
    @endif
</div>
