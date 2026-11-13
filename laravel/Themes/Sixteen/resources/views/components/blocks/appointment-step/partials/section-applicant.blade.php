{{--
    Sezione "applicant-card" (JSON: name, tax_label, tax_code, toggle_label, collapse_id, summary{title, rows[], edit, edit_class, class, footer_class}):
    scheda del richiedente autenticato con accordion "Mostra tutto" (controller `collapse` del tema, markup del riferimento).
--}}
<div class="cmp-info-button-card">
    <div class="card p-3 p-lg-4">
        <div class="card-body p-0">
            <h3 class="big-title mb-0">{{ $section['name'] ?? '' }}</h3>
            <p class="card-info">{{ $section['tax_label'] ?? '' }} <br>
                <span>{{ $section['tax_code'] ?? '' }}</span></p>
            <div class="accordion-item">
                <div class="accordion-header" id="heading-{{ $section['collapse_id'] ?? '' }}">
                    <button class="collapsed accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $section['collapse_id'] ?? '' }}" aria-expanded="false" aria-controls="{{ $section['collapse_id'] ?? '' }}">
                        <span class="d-flex align-items-center"> {{ $section['toggle_label'] ?? '' }}
                            <svg class="icon icon-primary icon-sm"><use href="{{ $icon('it-expand') }}"></use></svg></span>
                    </button>
                </div>
                <div id="{{ $section['collapse_id'] ?? '' }}" class="accordion-collapse collapse" role="region">
                    <div class="accordion-body p-0">
                        @include('pub_theme::components.blocks.appointment-step.partials.info-summary', [
                            'class' => $section['summary']['class'] ?? 'bg-white',
                            'title' => $section['summary']['title'] ?? '',
                            'rows' => $section['summary']['rows'] ?? [],
                            'edit' => $section['summary']['edit'] ?? null,
                            'editClass' => $section['summary']['edit_class'] ?? 'text-decoration-none',
                            'footerClass' => $section['summary']['footer_class'] ?? 'p-0',
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
