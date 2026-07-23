@props(['title' => '', 'subtitle' => '', 'description' => '', 'data' => []])
@php
    $title = $data['title'] ?? $title;
    $subtitle = $data['subtitle'] ?? $subtitle;
    $description = $data['content'] ?? $description;
@endphp

<div class="row justify-content-center row-shadow">
    <div class="col-12 col-lg-10">
        <div class="cmp-hero">
            <section class="it-hero-wrapper bg-white align-items-start">
                <div class="it-hero-text-wrapper pt-0 ps-0 pb-4 pb-lg-60">
                    <h1 class="text-black" data-element="page-name">{{ $title }}</h1>
                    @if($description || $subtitle)
                        <div class="hero-text">
                            <p>{{ $description ?: $subtitle }}</p>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
</div>
