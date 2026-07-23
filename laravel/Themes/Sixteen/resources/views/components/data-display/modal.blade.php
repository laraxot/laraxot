@props([
    'id' => null,
    'title' => '',
    'size' => 'md',
    'scrollable' => false,
    'centered' => false,
    'staticBackdrop' => false,
    'focus' => true,
    'keyboard' => true,
])

@php
    $id = $id ?? 'modal-' . uniqid();
    $sizeClasses = [
        'sm' => 'modal-sm',
        'md' => '',
        'lg' => 'modal-lg',
        'xl' => 'modal-xl',
    ];
    $modalClasses = [
        'modal fade',
        $scrollable ? 'modal-dialog-scrollable' : '',
        $centered ? 'modal-dialog-centered' : '',
    ];
@endphp

<div 
    class="{{ implode(' ', $modalClasses) }}" 
    id="{{ $id }}" 
    tabindex="-1" 
    aria-labelledby="{{ $id }}Label" 
    aria-hidden="true"
    @if($staticBackdrop) data-bs-backdrop="static" @endif
    @if(!$keyboard) data-bs-keyboard="false" @endif
    @if(!$focus) data-bs-focus="false" @endif
>
    <div class="modal-dialog {{ $sizeClasses[$size] }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">
                    {{ $title }}
                </h5>
                <button 
                    type="button" 
                    class="btn-close" 
                    data-bs-dismiss="modal" 
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            @if(isset($footer))
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modalEl = document.getElementById('{{ $id }}');
                if (modalEl) {
                    modalEl.addEventListener('shown.bs.modal', () => {
                        const focusable = modalEl.querySelectorAll('[autofocus]');
                        if (focusable.length > 0) {
                            focusable[0].focus();
                        }
                    });
                }
            });
        </script>
    @endpush
@endonce
