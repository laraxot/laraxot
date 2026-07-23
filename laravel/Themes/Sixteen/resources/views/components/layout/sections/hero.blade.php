{{-- 
/**
 * Hero Component - Bootstrap Italia Compliant
 * 
 * Prominent content presentation component (formerly Jumbotron)
 * Used for featured content, landing page headers, and call-to-action sections
 * 
 * @param string $type Hero type: 'image', 'text', 'background', 'overlay' (default: 'text')
 * @param string $title Main heading text
 * @param string $subtitle Optional subtitle/category text
 * @param string $description Hero description text
 * @param string $image Image URL for image-based heroes
 * @param string $imageAlt Alt text for hero image
 * @param string $imageTitle Title for hero image
 * @param string $ctaText Call-to-action button text
 * @param string $ctaUrl Call-to-action button URL
 * @param string $ctaClass Additional CSS classes for CTA button
 * @param string $bgClass Background class (bg-dark, bg-primary, etc.)
 * @param string $ariaLabel Accessible label for the section
 * @param bool $centered Whether to center the content
 * @param bool $small Whether to use small version
 * @param string $headingLevel Heading level (h1, h2, h3, etc.) default: h1
 */
--}}

@props([
    'type' => 'text',
    'title' => '',
    'subtitle' => '',
    'description' => '',
    'image' => '',
    'imageAlt' => '',
    'imageTitle' => '',
    'ctaText' => '',
    'ctaUrl' => '#',
    'ctaClass' => 'btn btn-sm btn-outline-primary',
    'bgClass' => 'bg-dark',
    'ariaLabel' => 'In evidenza',
    'centered' => false,
    'small' => false,
    'headingLevel' => 'h1'
])

@php
    $wrapperClasses = collect(['it-hero-wrapper']);
    if ($small) $wrapperClasses->push('it-hero-small-size');
    if ($type === 'background' && $image) $wrapperClasses->push('it-overlay');
    
    $textWrapperClasses = collect(['it-hero-text-wrapper']);
    $textWrapperClasses->push($bgClass);
    if ($centered) $textWrapperClasses->push('text-center');
@endphp

<section class="{{ $wrapperClasses->implode(' ') }}" 
         @if($type === 'image' && !$title) aria-label="{{ $ariaLabel }}" @endif
         @if($type === 'background' && $image) style="background-image: url('{{ $image }}');" @endif>

    @if($type === 'image' && !$title)
        {{-- Image-only hero --}}
        <div class="img-responsive-wrapper">
            <div class="img-responsive">
                <div class="img-wrapper">
                    <img src="{{ $image }}" 
                         title="{{ $imageTitle ?: $title }}" 
                         alt="{{ $imageAlt ?: $title }}"
                         class="img-fluid">
                </div>
            </div>
        </div>
    @else
        {{-- Text-based hero --}}
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="{{ $textWrapperClasses->implode(' ') }}">
                        @if($subtitle)
                            <span class="it-category">{{ $subtitle }}</span>
                        @endif
                        
                        @if($title)
                            <{{ $headingLevel }}>{{ $title }}</{{ $headingLevel }}>
                        @endif
                        
                        @if($description)
                            <p class="d-none d-lg-block">{{ $description }}</p>
                        @endif
                        
                        @if($ctaText && $ctaUrl)
                            <div class="it-btn-container">
                                <a class="{{ $ctaClass }}" href="{{ $ctaUrl }}">
                                    {{ $ctaText }}
                                </a>
                            </div>
                        @endif
                        
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
        
        @if($type === 'background' && $image)
            {{-- Background image with overlay --}}
            <div class="img-responsive-wrapper">
                <div class="img-responsive">
                    <div class="img-wrapper">
                        <img src="{{ $image }}" 
                             title="{{ $imageTitle ?: $title }}" 
                             alt="{{ $imageAlt ?: $title }}"
                             class="img-fluid">
                    </div>
                </div>
            </div>
        @endif
    @endif
</section>

{{-- 
Usage Examples:

1. Basic text hero:
<x-pub_theme::hero 
    title="Titolo della sezione"
    subtitle="Titolo occhiello"
    description="Platea dictumst vestibulum rhoncus est pellentesque elit ullamcorper dignissim cras."
    cta-text="Azione primaria"
    cta-url="/action" />

2. Image-only hero:
<x-pub_theme::hero 
    type="image"
    image="/path/to/hero-image.jpg"
    image-alt="Descrizione immagine"
    image-title="Titolo immagine" />

3. Hero with background image and overlay:
<x-pub_theme::hero 
    type="background"
    title="Titolo con sfondo"
    description="Testo sovrapposto all'immagine di sfondo"
    image="/path/to/background.jpg"
    bg-class="bg-primary"
    cta-text="Scopri di più" />

4. Centered hero:
<x-pub_theme::hero 
    title="Titolo Centrato"
    description="Contenuto centrato nella pagina"
    :centered="true"
    bg-class="bg-light" />

5. Small hero:
<x-pub_theme::hero 
    title="Hero Piccolo"
    :small="true"
    cta-text="Azione"
    cta-class="btn btn-primary" />

6. Custom content with slot:
<x-pub_theme::hero title="Titolo personalizzato">
    <div class="custom-content">
        <p>Contenuto personalizzato nel slot</p>
        <button class="btn btn-success">Azione custom</button>
    </div>
</x-pub_theme::hero>

7. Different heading level:
<x-pub_theme::hero 
    title="Sottosezione"
    heading-level="h2"
    bg-class="bg-secondary" />
--}}
