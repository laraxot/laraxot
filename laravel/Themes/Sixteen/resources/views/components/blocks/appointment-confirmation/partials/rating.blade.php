{{-- Valutazione del servizio: copia di blocks/partials/dc-rating con le classi di colonna del passo 6 (`px-4 p-lg-0`). JSON: feedback.*. --}}
<div class="bg-primary">
    <div class="container">
        <div class="row d-flex justify-content-center bg-primary">
            <div class="col-12 col-lg-6 px-4 p-lg-0">
                <div class="cmp-rating pt-lg-80 pb-lg-80" id="rating">
                    <div class="card shadow card-wrapper" data-element="feedback">
                        <div class="cmp-rating__card-first">
                            <div class="card-header border-0"><h2 class="title-medium-2-semi-bold mb-0" data-element="feedback-title">{{ $d('feedback.title') }}</h2></div>
                            <div class="card-body">
                                <fieldset class="rating">
                                    <legend class="visually-hidden">{{ $d('feedback.legend') }}</legend>
                                    @foreach ([5 => 'first-star', 4 => 'second-star', 3 => 'third-star', 2 => 'fourth-star', 1 => 'fifth-star'] as $star => $labelId)
                                        <input type="radio" id="star{{ $star }}a" name="ratingA" value="{{ $star }}">
                                        <label class="full rating-star active" for="star{{ $star }}a" data-element="feedback-rate-{{ $star }}">
                                            <svg class="icon icon-sm" role="img" aria-labelledby="{{ $labelId }}" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 1.7L9.5 9.2H1.6L8 13.9l-2.4 7.6 6.4-4.7 6.4 4.7-2.4-7.6 6.4-4.7h-7.9L12 1.7z"/><path fill="none" d="M0 0h24v24H0z"/></svg>
                                            <span class="visually-hidden" id="{{ $labelId }}">{{ sprintf($d('feedback.star_label'), $star) }}</span>
                                        </label>
                                    @endforeach
                                </fieldset>
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
                                                    <span class="d-block text-wrap" data-element="feedback-rating-question">{{ $d("feedback.$tone.question") }}</span>
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
                                                <span class="d-block text-wrap">{{ $d('feedback.detail.question') }}</span>
                                                <span class="step">2/2</span>
                                            </h3>
                                        </legend>
                                        <div class="cmp-steps-rating__body">
                                            <div class="form-group">
                                                <label for="formGroupExampleInputWithHelp" class="">{{ $d('feedback.detail.label') }}</label>
                                                <input type="text" class="form-control" id="formGroupExampleInputWithHelp" aria-describedby="formGroupExampleInputWithHelpDescription" maxlength="200" data-element="feedback-input-text">
                                                <small id="formGroupExampleInputWithHelpDescription" class="form-text">{{ $d('feedback.detail.help') }}</small>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
