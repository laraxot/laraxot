{{-- Passo con modulo (sito/segnalazione-02-dati): indice laterale e sezioni-scheda. Tipi di sezione: place, fields, person. --}}
<div class="container" id="main-container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            @include($parts.'.breadcrumb')
            <div class="cmp-heading pb-3 pb-lg-4"><h1 class="title-xxxlarge">{{ $d('title') }}</h1></div>
        </div>
        <div class="col-12">
            @include($parts.'.stepper')
            <p class="title-xsmall d-lg-none my-5">{{ $d('steps.required_notice') }}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-lg-3 d-lg-block mb-4 d-none">
            @include($parts.'.navscroll', ['nav' => $d('side_nav', [])])
        </div>
        <div class="col-12 col-lg-8 offset-lg-1">
            <div class="steppers-content" aria-live="polite">
                <div class="it-page-sections-container">
                    @foreach ($d('sections', []) as $section)
                        <section class="it-page-section" id="{{ $section['id'] ?? '' }}">
                            <div @class(['cmp-card', 'mb-40' => ! $loop->last])>
                                <div class="card has-bkg-grey shadow-sm{{ filled($section['card_class'] ?? null) ? ' '.$section['card_class'] : '' }}">
                                    <div class="card-header border-0 p-0 mb-lg-20 m-0">
                                        <div class="d-flex"><h2 @class(['title-xxlarge', 'mb-1' => ! ($section['required'] ?? false), 'mb-3 icon-required' => $section['required'] ?? false])>{{ $section['title'] ?? '' }}</h2></div>
                                        @if (filled($section['subtitle'] ?? null))<p class="subtitle-small mb-0">{{ $section['subtitle'] }}</p>@endif
                                    </div>
                                    <div class="card-body p-0">
                                        @include('pub_theme::components.blocks.report-step.section-'.($section['type'] ?? 'fields'), ['section' => $section])
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endforeach
                </div>
                @include($parts.'.steps-nav')
            </div>
        </div>
    </div>
</div>
