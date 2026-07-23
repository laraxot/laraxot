@props([
    'title' => 'Quanto sono chiare le informazioni su questa pagina?',
    'thanks_title' => 'Grazie, il tuo parere ci aiuterà a migliorare il servizio!',
    'positive_title' => 'Quali sono stati gli aspetti che hai preferito? 1/2',
    'negative_title' => 'Dove hai incontrato le maggiori difficoltà? 1/2',
    'details_title' => 'Vuoi aggiungere altri dettagli? 2/2',
    'positive_options' => [],
    'negative_options' => [],
    'details_label' => 'Dettaglio',
    'details_help' => 'Inserire massimo 200 caratteri',
    'back_label' => 'Indietro',
    'next_label' => 'Avanti',
])

<section class="section section-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="cmp-rating mb-5">
                    <div class="card-wrapper card shadow-sm">
                        <div class="card-body">
                            <h2 class="mb-4">{{ $title }}</h2>
                            <div class="rating mb-3" role="group" aria-label="{{ $title }}">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="rating-star-{{ $i }}" name="rating" value="{{ $i }}" class="visually-hidden">
                                    <label for="rating-star-{{ $i }}" aria-label="Valuta {{ $i }} stelle su 5">Valuta {{ $i }} stelle su 5</label>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cmp-rating mb-5">
                    <div class="card-wrapper card shadow-sm">
                        <div class="card-body">
                            <h2 class="mb-4">{{ $thanks_title }}</h2>

                            <div class="row">
                                <div class="col-12 col-lg-6">
                                    <h3 class="h5">{{ $positive_title }}</h3>
                                    @foreach($positive_options as $index => $option)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="positive-{{ $index }}">
                                            <label class="form-check-label" for="positive-{{ $index }}">{{ $option }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="col-12 col-lg-6">
                                    <h3 class="h5">{{ $negative_title }}</h3>
                                    @foreach($negative_options as $index => $option)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="negative-{{ $index }}">
                                            <label class="form-check-label" for="negative-{{ $index }}">{{ $option }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-4">
                                <h3 class="h5">{{ $details_title }}</h3>
                                <label for="feedback-details" class="form-label">{{ $details_label }}</label>
                                <textarea id="feedback-details" class="form-control" rows="4"></textarea>
                                <p class="form-text">{{ $details_help }}</p>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-primary">{{ $back_label }}</button>
                                <button type="button" class="btn btn-primary">{{ $next_label }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
