{{--
    Appointment Confirmation Block
    Reference: design-comuni-pagine-statiche appointment confirmation page
--}}
@props([
    'title' => 'Appuntamento confermato',
    'description' => '',
    'reference_number' => '',
    'details' => [],
    'next_steps' => [],
])

<section class="section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                {{-- Success Icon --}}
                <div class="text-center mb-4">
                    <div class="icon-success mb-3">
                        <svg class="icon icon-lg text-success" aria-hidden="true">
                            <use href="#it-check-circle"></use>
                        </svg>
                    </div>
                    <h1 class="mb-2">{{ $title }}</h1>
                    @if($description)
                        <p class="text-muted">{{ $description }}</p>
                    @endif
                </div>

                {{-- Reference Number --}}
                @if($reference_number)
                <div class="alert alert-info mb-4">
                    <div class="d-flex align-items-center">
                        <svg class="icon icon-sm me-2"><use href="#it-info-circle"></use></svg>
                        <span>Codice di prenotazione: <strong>{{ $reference_number }}</strong></span>
                    </div>
                </div>
                @endif

                {{-- Details Card --}}
                @if(!empty($details))
                <div class="card shadow-sm rounded mb-4">
                    <div class="card-header bg-light">
                        <h2 class="h5 mb-0">Dettagli appuntamento</h2>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            @foreach($details as $key => $value)
                                <dt class="col-sm-4 text-muted">{{ $key }}</dt>
                                <dd class="col-sm-8 mb-0">{{ $value }}</dd>
                            @endforeach
                        </dl>
                    </div>
                </div>
                @endif

                {{-- Next Steps --}}
                @if(!empty($next_steps))
                <div class="card shadow-sm rounded">
                    <div class="card-header bg-light">
                        <h2 class="h5 mb-0">Cosa portare</h2>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            @foreach($next_steps as $step)
                                <li class="mb-2">{{ $step }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                {{-- Actions --}}
                <div class="text-center mt-4">
                    <a href="/" class="btn btn-primary">Torna alla home</a>
                </div>
            </div>
        </div>
    </div>
</section>