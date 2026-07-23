{{-- Service Header Component --}}
@props([
    'title' => '',
    'subtitle' => '',
    'description' => '',
    'category' => '',
])

<div class="cmp-heading mt-2 mb-8">
    <div class="category-badge mb-2">
        <span class="badge badge-pill badge-outline-primary">
            {{ $category }}
        </span>
    </div>
    <h1 class="title-xxxlarge mb-2">{{ $title }}</h1>
    @if($subtitle)
    <p class="subtitle-small">{{ $subtitle }}</p>
    @endif
    @if($description)
    <p class="lead mt-4">{{ $description }}</p>
    @endif
</div>
