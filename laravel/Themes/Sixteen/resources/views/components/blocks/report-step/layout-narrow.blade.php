{{-- Passo di consenso (sito/segnalazione-01-privacy): titolo, passi, paragrafi, casella di consenso e pulsante. Tipi: paragraph, checkbox, button. --}}
<div class="container" id="main-container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            @include($parts.'.breadcrumb')
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10"><h1 class="title-xxxlarge mb-4">{{ $d('title') }}</h1></div>
        <div class="col-12">
            @include($parts.'.stepper')
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 pb-40 pb-lg-80">
            @foreach ($d('sections', []) as $section)
                @switch($section['type'] ?? '')
                    @case('paragraph')
                        <p class="text-paragraph {{ $section['class'] ?? '' }}"> {{ $section['text'] ?? '' }}
                            @if (filled($section['link'] ?? null))<a href="{{ $section['link']['url'] ?? '#' }}" class="t-primary">{{ $section['link']['label'] ?? '' }}</a>@endif
                        </p>
                        @break
                    @case('checkbox')
                        <div class="form-check mt-4 mb-3 mt-md-40 mb-lg-40">
                            <div class="checkbox-body d-flex align-items-center">
                                <input type="checkbox" id="{{ $section['id'] ?? '' }}" name="{{ $section['name'] ?? '' }}" value="{{ $section['value'] ?? '' }}">
                                <label class="title-small-semi-bold pt-1" for="{{ $section['id'] ?? '' }}">{{ $section['label'] ?? '' }}</label>
                            </div>
                        </div>
                        @break
                    @case('button')
                        <button type="button" class="btn btn-primary mobile-full"><span class="">{{ $section['label'] ?? '' }}</span></button>
                        @break
                @endswitch
            @endforeach
        </div>
    </div>
</div>
