{{-- Bootstrap Italia Hero Component --}}

@if($type === 'image')
<section class="hero hero-image" style="background-image: url('{{ $image }}');">
    <div class="hero-overlay">
        <div class="container-italia">
            <div class="hero-content">
                @if(isset($title))
                <h1 class="hero-title">{{ $title }}</h1>
                @endif
                @if(isset($subtitle))
                <p class="hero-subtitle">{{ $subtitle }}</p>
                @endif
                @if(isset($imageAlt))
                <span class="visually-hidden">{{ $imageAlt }}</span>
                @endif
            </div>
        </div>
    </div>
</section>
@else
<section class="hero hero-text">
    <div class="container-italia">
        <div class="hero-content">
            @if(isset($title))
            <h1 class="hero-title">{{ $title }}</h1>
            @endif
            @if(isset($subtitle))
            <p class="hero-subtitle">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
</section>
@endif
