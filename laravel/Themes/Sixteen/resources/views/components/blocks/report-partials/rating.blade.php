{{--
    Valutazione a stelle del riferimento (comportamento: `dcRating`, montato per selettore `.cmp-rating#rating`). Come il partial condiviso
    dc-rating, ma con la classe della colonna dal JSON (`feedback.col_class`, default `col-12 col-lg-6 p-lg-0 px-4`): in queste pagine la
    colonna del riferimento ha `p-lg-0` e un padding laterale che il partial condiviso non prevede.
--}}
<div class="bg-primary">
    <div class="container">
        <div class="row d-flex justify-content-center bg-primary">
            <div class="{{ $d('feedback.col_class', 'col-12 col-lg-6 p-lg-0 px-4') }}">
                <div class="cmp-rating pt-lg-80 pb-lg-80" id="rating">
                    <div class="card shadow card-wrapper" data-element="feedback">
                        <div class="cmp-rating__card-first">
                            <div class="card-header border-0"><h2 class="title-medium-2-semi-bold mb-0" data-element="feedback-title">{{ $d('feedback.title') }}</h2></div>
                            <div class="card-body">
                                @include('pub_theme::components.blocks.partials.dc-rating-stars', [
                                    'legend' => $d('feedback.legend'),
                                    'starLabels' => collect(range(1, 5))->mapWithKeys(fn (int $star): array => [$star => sprintf((string) $d('feedback.star_label'), $star)])->all(),
                                ])
                            </div>
                        </div>
                        <div class="cmp-rating__card-second d-none" data-step="3">
                            <div class="card-header border-0 mb-0"><h2 class="title-medium-2-bold mb-0" id="rating-feedback">{{ $d('feedback.thanks') }}</h2></div>
                        </div>
                        <div class="form-rating d-none">
                            <div class="d-none" data-step="1">
                                <div class="cmp-steps-rating">
                                    @foreach (['positive' => ['one', 'rating1', 1], 'negative' => ['two', 'rating2', 6]] as $tone => [$suffix, $group, $firstId])
                                        <fieldset class="fieldset-rating-{{ $suffix }} d-none" data-element="feedback-rating-{{ $tone }}">
                                            <legend class="iscrizioni-header w-100">
                                                <h3 @class(['step-title', 'd-flex', 'flex-column', 'flex-lg-row', 'flex-wrap' => $tone === 'negative', 'align-items-lg-center', 'justify-content-between', 'drop-shadow'])>
                                                    <span class="d-block text-wrap" data-element="feedback-rating-question"> {{ $d("feedback.$tone.question") }} </span>
                                                    <span class="step">1/2</span>
                                                </h3>
                                            </legend>
                                            <div class="cmp-steps-rating__body">
                                                <div class="cmp-radio-list">
                                                    <div class="card card-teaser shadow-rating">
                                                        <div class="card-body">
                                                            <div class="form-check m-0">
                                                                @foreach ($d("feedback.$tone.answers", []) as $answer)
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
                                                <span class="d-block text-wrap"> {{ $d('feedback.detail.question') }} </span>
                                                <span class="step">2/2</span>
                                            </h3>
                                        </legend>
                                        <div class="cmp-steps-rating__body">
                                            <div class="form-group">
                                                <label for="formGroupExampleInputWithHelp" class="">{{ $d('feedback.detail.label') }}</label>
                                                <input type="text" class="form-control" id="formGroupExampleInputWithHelp" aria-describedby="formGroupExampleInputWithHelpDescription" maxlength="200" data-element="feedback-input-text">
                                                <small id="formGroupExampleInputWithHelpDescription" class="form-text"> {{ $d('feedback.detail.help') }}</small>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="d-flex flex-nowrap pt-4 w-100 justify-content-center button-shadow">
                                <button class="btn btn-outline-primary fw-bold me-4 btn-back" type="button">{{ $d('feedback.back') }}</button>
                                <button class="btn btn-primary fw-bold btn-next" type="submit" form="rating">{{ $d('feedback.next') }}</button>
                            </div>
                        </div>
                        {{-- DCP-291 (FO-5-17): regione di stato nel DOM, si riempie al passo del ringraziamento (vedi partials/dc-rating). --}}
                        <div class="visually-hidden" role="status" x-text="step === 3 ? {{ Js::from((string) $d('feedback.thanks')) }} : ''"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
