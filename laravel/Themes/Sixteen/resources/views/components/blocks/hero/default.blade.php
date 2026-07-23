@props(['data' => []])

@php
    $title = $data['title'] ?? '';
    $subtitle = $data['subtitle'] ?? '';
@endphp

<div class="row justify-content-center">
    <div class="col-12 col-lg-10">
        <div class="cmp-hero">
            <section class="it-hero-wrapper bg-white align-items-start">
                <div class="it-hero-text-wrapper pt-0 ps-0 pb-4 pb-lg-60">
                    <h1 class="text-black" data-element="page-name">{{ $title }}</h1>
                    @if($subtitle)
                        <div class="hero-text">
                            <p>{{ $subtitle }}</p>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
</div>
