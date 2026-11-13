@props(['data' => []])
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    // Testi: dati del blocco (JSON CMS) se presenti, altrimenti fixcity::rating nella lingua corrente (come feedback/rating).
    // I blocchi en/de/es spesso non hanno `feedback`: senza riserva etichette e stelle uscivano vuote
    // (axe label + svg-img-alt su /en/blog, 2026-10-23).
    $ns = 'fixcity::rating.';
    $tx = static fn (string $key, string $trans): string => (string) (filled(data_get($data, $key)) ? data_get($data, $key) : __($ns.$trans));
    $answers = static fn (string $tone): array => filled(data_get($data, "feedback.$tone.answers"))
        ? (array) data_get($data, "feedback.$tone.answers")
        : array_map(static fn (int $i): string => (string) __($ns."fields.{$tone}_options.options.$i.label"), range(1, 5));
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $thanks = $tx('feedback.thanks', 'messages.thank_you.text');
    $thanksStep = 3;
@endphp
<div class="bg-primary">
    <div class="container">
        <div class="row d-flex justify-content-center bg-primary">
            <div class="col-12 col-lg-6{{ filled($d('feedback.col_class')) ? ' '.$d('feedback.col_class') : '' }}">
                <div class="cmp-rating pt-lg-80 pb-lg-80" id="rating">
                    <div class="card shadow card-wrapper" data-element="feedback">
                        <div class="cmp-rating__card-first">
                            <div class="card-header border-0"><h2 class="title-medium-2-semi-bold mb-0" data-element="feedback-title">{{ $tx('feedback.title', 'fields.title.label') }}</h2></div>
                            <div class="card-body">
                                @include('pub_theme::components.blocks.partials.dc-rating-stars', [
                                    'legend' => $tx('feedback.legend', 'fields.star.legend.label'),
                                    'starLabels' => collect(range(1, 5))->mapWithKeys(fn (int $star): array => [
                                        $star => filled($d('feedback.star_label')) ? sprintf($d('feedback.star_label'), $star) : __($ns.'fields.star.labels.'.$star.'.label'),
                                    ])->all(),
                                ])
                            </div>
                        </div>
                        <div class="cmp-rating__card-second d-none" data-step="{{ $thanksStep }}">
                            <div class="card-header border-0 mb-0"><h2 class="title-medium-2-bold mb-0" id="rating-feedback">{{ $thanks }}</h2></div>
                        </div>
                        <div class="form-rating d-none">
                            <div class="d-none" data-step="1">
                                <div class="cmp-steps-rating">
                                    @foreach (['positive' => ['one', 'rating1', 1], 'negative' => ['two', 'rating2', 6]] as $tone => [$suffix, $group, $firstId])
                                        <fieldset class="fieldset-rating-{{ $suffix }} d-none" data-element="feedback-rating-{{ $tone }}">
                                            <legend class="iscrizioni-header w-100">
                                                <h3 @class(['step-title', 'd-flex', 'flex-column', 'flex-lg-row', 'flex-wrap' => $tone === 'negative', 'align-items-lg-center', 'justify-content-between', 'drop-shadow'])>
                                                    <span class="d-block text-wrap" data-element="feedback-rating-question">{{ $tx("feedback.$tone.question", "fields.{$tone}_question.label") }}</span>
                                                    <span class="step">1/2</span>
                                                </h3>
                                            </legend>
                                            <div class="cmp-steps-rating__body">
                                                <div class="cmp-radio-list">
                                                    <div class="card card-teaser shadow-rating">
                                                        <div class="card-body">
                                                            <div class="form-check m-0">
                                                                @foreach ($answers($tone) as $answer)
                                                                    <div class="radio-body border-bottom border-light cmp-radio-list__item">
                                                                        <input name="{{ $group }}" type="radio" id="radio-{{ $firstId + $loop->index }}">
                                                                        <label for="radio-{{ $firstId + $loop->index }}" class="active" data-element="feedback-rating-answer">{{ $answer }}</label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    @endforeach
                                </div>
                            </div>
                            <div class="d-none" data-step="2">
                                <div class="cmp-steps-rating">
                                    <fieldset>
                                        <legend class="iscrizioni-header w-100">
                                            <h3 class="step-title d-flex flex-column flex-lg-row flex-wrap align-items-lg-center justify-content-between drop-shadow">
                                                <span class="d-block text-wrap">{{ $tx('feedback.detail.question', 'fields.text_question.label') }}</span>
                                                <span class="step">2/2</span>
                                            </h3>
                                        </legend>
                                        <div class="cmp-steps-rating__body">
                                            <div class="form-group">
                                                <label for="formGroupExampleInputWithHelp" class="">{{ $tx('feedback.detail.label', 'fields.text_field.label.label') }}</label>
                                                <input type="text" class="form-control" id="formGroupExampleInputWithHelp" aria-describedby="formGroupExampleInputWithHelpDescription" maxlength="200" data-element="feedback-input-text">
                                                <small id="formGroupExampleInputWithHelpDescription" class="form-text">{{ $tx('feedback.detail.help', 'fields.text_field.help_text.text') }}</small>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="d-flex flex-nowrap pt-4 w-100 justify-content-center button-shadow">
                                <button class="btn btn-outline-primary fw-bold me-4 btn-back" type="button">{{ $tx('feedback.back', 'actions.back.label') }}</button>
                                <button class="btn btn-primary fw-bold btn-next" type="submit" form="rating">{{ $tx('feedback.next', 'actions.next.label') }}</button>
                            </div>
                        </div>
                        {{-- DCP-291 (FO-5-17): regione di stato che resta nel DOM (fuori dalle card che si nascondono con `d-none`) e si riempie quando
                             `dcRating` arriva al passo del ringraziamento (`data-step="{{ $thanksStep }}"`); una card che compare da sola non viene annunciata. --}}
                        <div class="visually-hidden" role="status" x-text="step === {{ $thanksStep }} ? {{ Js::from($thanks) }} : ''"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
