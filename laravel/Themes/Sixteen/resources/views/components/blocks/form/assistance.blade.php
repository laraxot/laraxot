{{--
    Assistance Request Form Block
    Reference: design-comuni-pagine-statiche assistenza page
--}}
@props([
    'title' => 'Inserisci i tuoi dati',
    'description' => '',
    'fields' => [],
    'action' => '#',
    'method' => 'POST',
])

<section class="section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm rounded">
                    <div class="card-body p-4 p-lg-5">
                        @if(!empty($title))
                            <h2 class="mb-2">{{ $title }}</h2>
                        @endif
                        
                        @if(!empty($description))
                            <p class="text-muted mb-4">{{ $description }}</p>
                        @endif
                        
                        <form action="{{ $action }}" method="{{ $method }}" class="needs-validation" novalidate>
                            @csrf
                            
                            @foreach($fields as $field)
                                <div class="mb-3">
                                    @switch($field['type'] ?? 'text')
                                        @case('textarea')
                                            <label for="{{ $field['name'] }}" class="form-label">
                                                {{ $field['label'] ?? '' }}
                                                @if(!empty($field['required']))
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            <textarea 
                                                class="form-control" 
                                                id="{{ $field['name'] }}" 
                                                name="{{ $field['name'] }}"
                                                rows="5"
                                                {{ !empty($field['required']) ? 'required' : '' }}
                                            ></textarea>
                                            @break
                                            
                                        @case('select')
                                            <label for="{{ $field['name'] }}" class="form-label">
                                                {{ $field['label'] ?? '' }}
                                                @if(!empty($field['required']))
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            <select 
                                                class="form-select" 
                                                id="{{ $field['name'] }}" 
                                                name="{{ $field['name'] }}"
                                                {{ !empty($field['required']) ? 'required' : '' }}
                                            >
                                                <option value="">Seleziona...</option>
                                                @if(!empty($field['options']))
                                                    @foreach($field['options'] as $option)
                                                        <option value="{{ $option['value'] ?? '' }}">
                                                            {{ $option['label'] ?? '' }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @break
                                            
                                        @case('checkbox')
                                            <div class="form-check">
                                                <input 
                                                    class="form-check-input" 
                                                    type="checkbox" 
                                                    id="{{ $field['name'] }}" 
                                                    name="{{ $field['name'] }}"
                                                    {{ !empty($field['required']) ? 'required' : '' }}
                                                >
                                                <label class="form-check-label" for="{{ $field['name'] }}">
                                                    {{ $field['label'] ?? '' }}
                                                </label>
                                            </div>
                                            @break
                                            
                                        @default
                                            <label for="{{ $field['name'] }}" class="form-label">
                                                {{ $field['label'] ?? '' }}
                                                @if(!empty($field['required']))
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            <input 
                                                type="{{ $field['type'] ?? 'text' }}" 
                                                class="form-control" 
                                                id="{{ $field['name'] }}" 
                                                name="{{ $field['name'] }}"
                                                {{ !empty($field['required']) ? 'required' : '' }}
                                            >
                                    @endswitch
                                </div>
                            @endforeach
                            
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    Invia richiesta
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>