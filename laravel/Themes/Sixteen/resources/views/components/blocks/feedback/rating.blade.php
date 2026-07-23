@props(['data' => []])

@php
    $ns = 'fixcity::rating';

    $t = function (mixed $value, string $fallbackKey) use ($ns): string {
        if (is_array($value)) {
            $value = $value[app()->getLocale()] ?? null;
        }
        if (empty($value)) {
            return __($ns.'.'.$fallbackKey);
        }
        if (str_contains((string) $value, '::')) {
            return __((string) $value);
        }

        return (string) $value;
    };

    $title = $t($data['title'] ?? null, 'fields.title.label');
    $subtitle = $t($data['subtitle'] ?? null, 'fields.subtitle.label');
    $starLegend = $t($data['star_legend'] ?? null, 'fields.star.legend.label');
    $textLabel = $t($data['text_label'][app()->getLocale()] ?? null, 'fields.text_field.label.label');
    $textHelp = $t($data['text_help'][app()->getLocale()] ?? null, 'fields.text_field.help_text.text');
    $btnBack = $t($data['btn_back'][app()->getLocale()] ?? null, 'actions.back.label');
    $btnNext = $t($data['btn_next'][app()->getLocale()] ?? null, 'actions.next.label');
    $thankYouMsg = $t($data['thank_you'][app()->getLocale()] ?? null, 'messages.thank_you.text');

    $positiveQuestion = $t($data['positive_question'][app()->getLocale()] ?? null, 'fields.positive_question.label');
    $negativeQuestion = $t($data['negative_question'][app()->getLocale()] ?? null, 'fields.negative_question.label');

    $positiveOptions = [];
    for ($i = 1; $i <= 5; $i++) {
        $positiveOptions[$i] = __($ns.'.fields.positive_options.options.'.$i.'.label');
    }

    $negativeOptions = [];
    for ($i = 1; $i <= 5; $i++) {
        $negativeOptions[$i] = __($ns.'.fields.negative_options.options.'.$i.'.label');
    }

    $starLabels = [];
    for ($i = 1; $i <= 5; $i++) {
        $starLabels[$i] = __($ns.'.fields.star.labels.'.$i.'.label');
    }

    $starAriaIds = [
        5 => 'first-star',
        4 => 'second-star',
        3 => 'third-star',
        2 => 'fourth-star',
        1 => 'fifth-star',
    ];
@endphp

<div class="bg-primary">
    <div class="container">
        <div class="row d-flex justify-content-center bg-primary">
            <div class="col-12 col-lg-6 p-lg-0 px-4">
                <div
                    id="rating"
                    class="cmp-rating pt-lg-80 pb-lg-80"
                    x-data="{
                        rating: 0,
                        hover: 0,
                        step: 1,
                        feedbackType: '',
                        selectedOption: '',
                        textFeedback: ''
                    }"
                >
                    <div class="card shadow card-wrapper" data-element="feedback">
                        <div class="cmp-rating__card-first">
                            <div class="card-header border-0">
                                <h2 class="title-medium-2-semi-bold mb-0" data-element="feedback-title">
                                    {{ $title }}
                                </h2>
                            </div>

                            <div class="card-body">
                                <fieldset class="rating">
                                    <legend class="visually-hidden">{{ $starLegend }}</legend>
                                    @for ($star = 5; $star >= 1; $star--)
                                        <input type="radio" id="star{{ $star }}a" name="ratingA" value="{{ $star }}" x-model="rating">
                                        <label
                                            class="full rating-star"
                                            for="star{{ $star }}a"
                                            data-element="feedback-rate-{{ $star }}"
                                            @click="feedbackType = {{ $star }} >= 4 ? 'positive' : 'negative'; step = 2"
                                            @mouseenter="hover = {{ $star }}"
                                            @mouseleave="hover = 0"
                                        >
                                            <svg class="icon icon-sm" role="img" aria-labelledby="{{ $starAriaIds[$star] }}" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 1.7L9.5 9.2H1.6L8 13.9l-2.4 7.6 6.4-4.7 6.4 4.7-2.4-7.6 6.4-4.7h-7.9L12 1.7z"></path>
                                                <path fill="none" d="M0 0h24v24H0z"></path>
                                            </svg>
                                            <span class="visually-hidden" id="{{ $starAriaIds[$star] }}">{{ $starLabels[$star] }}</span>
                                        </label>
                                    @endfor
                                </fieldset>
                            </div>
                        </div>

                        <div class="cmp-rating__card-second d-none" :class="{ 'd-none': step !== 2 }">
                            <div class="card-header border-0 mb-0">
                                <h2 class="title-medium-2-bold mb-0" id="rating-feedback">{{ $subtitle }}</h2>
                            </div>

                            <div class="form-rating d-none" :class="{ 'd-none': step !== 2 }">
                                <div class="cmp-steps-rating">
                                    <fieldset
                                        class="fieldset-rating-one d-none"
                                        :class="{ 'd-none': feedbackType !== 'positive' }"
                                        data-element="feedback-rating-positive"
                                    >
                                        <legend class="iscrizioni-header w-100">
                                            <h3 class="step-title d-flex flex-column flex-lg-row align-items-lg-center justify-content-between drop-shadow">
                                                <span class="d-block text-wrap" data-element="feedback-rating-question">{{ $positiveQuestion }}</span>
                                                <span class="step"></span>
                                            </h3>
                                        </legend>
                                        <div class="cmp-steps-rating__body">
                                            <div class="cmp-radio-list">
                                                <div class="card card-teaser shadow-rating">
                                                    <div class="card-body">
                                                        @foreach ($positiveOptions as $index => $option)
                                                            <div class="form-check m-0">
                                                                <div class="radio-body border-bottom border-light cmp-radio-list__item">
                                                                    <input name="rating1" type="radio" id="radio-{{ $index }}" value="{{ $index }}" x-model="selectedOption">
                                                                    <label for="radio-{{ $index }}" class="active" data-element="feedback-rating-answer">{{ $option }}</label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>

                                <div class="cmp-steps-rating">
                                    <fieldset
                                        class="fieldset-rating-two d-none"
                                        :class="{ 'd-none': feedbackType !== 'negative' }"
                                        data-element="feedback-rating-negative"
                                    >
                                        <legend class="iscrizioni-header w-100">
                                            <h3 class="step-title d-flex flex-column flex-lg-row flex-wrap align-items-lg-center justify-content-between drop-shadow">
                                                <span class="d-block text-wrap" data-element="feedback-rating-question">{{ $negativeQuestion }}</span>
                                                <span class="step"></span>
                                            </h3>
                                        </legend>
                                        <div class="cmp-steps-rating__body">
                                            <div class="cmp-radio-list">
                                                <div class="card card-teaser shadow-rating">
                                                    <div class="card-body">
                                                        @foreach ($negativeOptions as $index => $option)
                                                            <div class="form-check m-0">
                                                                <div class="radio-body border-bottom border-light cmp-radio-list__item">
                                                                    <input name="rating2" type="radio" id="radio-{{ $index + 5 }}" value="{{ $index }}" x-model="selectedOption">
                                                                    <label for="radio-{{ $index + 5 }}" class="active" data-element="feedback-rating-answer">{{ $option }}</label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>

                                <div class="cmp-steps-rating">
                                    <div class="cmp-steps-rating__body">
                                        <div class="form-group">
                                            <label for="formGroupExampleInputWithHelp" class="">{{ $textLabel }}</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="formGroupExampleInputWithHelp"
                                                aria-describedby="formGroupExampleInputWithHelpDescription"
                                                maxlength="200"
                                                x-model="textFeedback"
                                                data-element="feedback-input-text"
                                            >
                                            <small id="formGroupExampleInputWithHelpDescription" class="form-text">
                                                {{ $textHelp }}
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex flex-nowrap pt-4 w-100 justify-content-center button-shadow">
                                    <button class="btn btn-outline-primary fw-bold me-4 btn-back" type="button" @click="step = 1; selectedOption = ''; textFeedback = ''">{{ $btnBack }}</button>
                                    <button class="btn btn-primary fw-bold btn-next" type="button" @click="step = 3">{{ $btnNext }}</button>
                                </div>
                            </div>
                        </div>

                        <div class="cmp-rating__card-second d-none" data-step="3" :class="{ 'd-none': step !== 3 }">
                            <fieldset class="d-none">
                                <legend class="visually-hidden">{{ $textLabel }}</legend>
                                <div class="card-header border-0 mb-0">
                                    <h2 class="title-medium-2-bold mb-0">{{ $thankYouMsg }}</h2>
                                </div>
                                <div class="card-body">
                                    <label for="rating-step3-text" class="form-label">{{ $textLabel }}</label>
                                    <textarea
                                        id="rating-step3-text"
                                        class="form-control"
                                        rows="3"
                                        maxlength="200"
                                        x-model="textFeedback"
                                        data-element="feedback-input-text"
                                    ></textarea>
                                    <button class="btn btn-primary fw-bold btn-next mt-3" type="button">
                                        {{ __('fixcity::rating.actions.submit.label') }}
                                    </button>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
