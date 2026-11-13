{{-- Sezione "form" (JSON: fields[id, name, type, label, help, required]): campi di testo del modulo Design Comuni (cmp-input). --}}
<div class="form-wrapper bg-white p-4">
    @foreach ($section['fields'] ?? [] as $field)
        <div class="form-group cmp-input mb-0">
            <label class="cmp-input__label" for="{{ $field['id'] ?? '' }}">{{ $field['label'] ?? '' }}</label>
            <input type="{{ $field['type'] ?? 'text' }}" class="form-control" id="{{ $field['id'] ?? '' }}" name="{{ $field['name'] ?? '' }}"{{ ($field['required'] ?? false) ? ' required' : '' }}>
            <div class="d-flex"><span class="form-text cmp-input__text"> {{ $field['help'] ?? '' }}</span></div>
        </div>
    @endforeach
</div>
