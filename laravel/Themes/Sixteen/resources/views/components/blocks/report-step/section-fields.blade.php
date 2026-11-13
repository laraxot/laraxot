{{-- Sezione di campi: tipi select, text, textarea, upload (JSON: section.fields[type, id, name, label, options, ...]). --}}
@foreach ($section['fields'] ?? [] as $field)
    @switch($field['type'] ?? '')
        @case('select')
            <div class="select-wrapper p-md-3 p-lg-4 pb-lg-0 select-partials">
                <label for="{{ $field['id'] ?? '' }}" class="visually-hidden">{{ $field['label'] ?? '' }}</label>
                <select id="{{ $field['id'] ?? '' }}" class="u-grey-dark" required>
                    <option selected="selected" value="">{{ $field['placeholder'] ?? '' }}</option>
                    @foreach ($field['options'] ?? [] as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
            </div>
            @break
        @case('text')
            <div class="text-area-wrapper p-3 px-lg-4 pt-lg-5 pb-lg-0 bg-white">
                <div class="form-group cmp-input mb-0">
                    <label class="cmp-input__label" for="{{ $field['id'] ?? '' }}">{{ $field['label'] ?? '' }}</label>
                    <input type="text" class="form-control" id="{{ $field['id'] ?? '' }}" name="{{ $field['id'] ?? '' }}" required>
                </div>
            </div>
            @break
        @case('textarea')
            <div class="cmp-text-area m-0 p-3 px-lg-4 pt-lg-5 pb-lg-4 bg-white">
                <div class="form-group">
                    <label for="{{ $field['id'] ?? '' }}" class="d-block">{{ $field['label'] ?? '' }}</label>
                    <textarea class="text-area" id="{{ $field['id'] ?? '' }}" rows="2" required></textarea>
                    <span class="label">{{ $field['help'] ?? '' }}</span>
                </div>
            </div>
            @break
        @case('upload')
            <div class="btn-wrapper px-3 pt-2 pb-3 px-lg-4 pb-lg-4 pt-lg-0 bg-white">
                <label class="title-xsmall-bold u-grey-dark pb-2 ms-2">{{ $field['label'] ?? '' }}</label>
                <div class="upload-wrapper d-flex justify-content-between align-items-center">
                    <img src="{{ $assets }}/images/{{ $field['thumbnail'] ?? '' }}" alt="" class="img">
                    <span class="t-primary fw-bold w-100 ms-2">{{ $field['file_name'] ?? '' }}</span>
                    <a href="#" class="align-self-center" aria-label="{{ $field['remove_label'] ?? '' }}">
                        <svg class="icon icon-primary icon-sm mb-1"><use href="{{ $icon('it-close') }}"></use></svg></a>
                </div>
                <hr>
                <button type="button" aria-label="{{ $field['button_aria'] ?? '' }}" class="btn btn-primary w-100 fw-bold">
                    <span class="rounded-icon"><svg class="icon icon-white icon-sm" aria-hidden="true"><use href="{{ $icon('it-upload') }}"></use></svg></span>
                    <span class="">{{ $field['button_label'] ?? '' }}</span></button>
                <p class="title-xsmall u-grey-dark pt-10 mb-0">{{ $field['help'] ?? '' }}</p>
            </div>
            @break
    @endswitch
@endforeach
