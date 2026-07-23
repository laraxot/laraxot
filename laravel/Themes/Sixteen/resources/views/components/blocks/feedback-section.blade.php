@props(['data' => []])

{{-- 
    Feedback Section Component - Design Comuni Style
    Usage: <x-blocks.feedback-section :data="$feedbackData" />
    
    Data structure:
    - title: string
    - description: string
--}}

@php
    $title = $data['title'] ?? 'Dicono di noi';
    $description = $data['description'] ?? 'Valuta la qualità della pagina';
@endphp

<section class="feedback-section py-8 bg-gray-50">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="title-xxlarge mb-4">{{ $title }}</h2>
                <p class="lead text-gray-600 mb-6">{{ $description }}</p>
                
                {{-- Star Rating --}}
                <div class="rating-stars mb-6">
                    <div class="d-inline-flex gap-2">
                        @for($i = 1; $i <= 5; $i++)
                        <button 
                            class="btn btn-link p-0 text-3xl hover:text-primary transition-colors"
                            aria-label="Valuta {{ $i }} stelle"
                        >
                            <x-filament::icon icon="heroicon-o-star" class="w-10 h-10" />
                        </button>
                        @endfor
                    </div>
                </div>
                
                {{-- Feedback Options --}}
                <div class="feedback-options">
                    <h3 class="h5 font-semibold mb-3">Aspetti positivi</h3>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <button class="btn btn-outline-primary btn-sm">Istruzioni chiare</button>
                        <button class="btn btn-outline-primary btn-sm">Completo</button>
                        <button class="btn btn-outline-primary btn-sm">Monitoraggio avanzamento</button>
                        <button class="btn btn-outline-primary btn-sm">Nessun problema tecnico</button>
                        <button class="btn btn-outline-primary btn-sm">Altro</button>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h3 class="h5 font-semibold mb-3">Difficoltà riscontrate</h3>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <button class="btn btn-outline-primary btn-sm">Istruzioni poco chiare</button>
                        <button class="btn btn-outline-primary btn-sm">Incompleto</button>
                        <button class="btn btn-outline-primary btn-sm">Confusione avanzamento</button>
                        <button class="btn btn-outline-primary btn-sm">Problemi tecnici</button>
                        <button class="btn btn-outline-primary btn-sm">Altro</button>
                    </div>
                </div>
                
                {{-- Details Textarea --}}
                <div class="mt-6">
                    <label for="feedback-details" class="form-label h5 font-semibold">Dettagli (max 200 caratteri)</label>
                    <textarea 
                        id="feedback-details" 
                        class="form-control" 
                        rows="3" 
                        maxlength="200"
                        placeholder="Descrivi la tua esperienza..."
                    ></textarea>
                </div>
                
                {{-- Navigation Buttons --}}
                <div class="mt-6">
                    <button class="btn btn-secondary me-2">Indietro</button>
                    <button class="btn btn-primary">Avanti</button>
                </div>
            </div>
        </div>
    </div>
</section>
