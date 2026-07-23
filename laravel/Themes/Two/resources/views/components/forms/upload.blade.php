@props([
    'name' => 'file',
    'label' => '',
    'accept' => '*',
    'multiple' => false,
    'maxSize' => 10240, // KB
    'required' => false,
    'helpText' => '',
])

<div class="form-upload">
    @if($label)
        <label class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div 
        class="upload-zone border-2 border-dashed rounded-lg p-8 text-center cursor-pointer transition-colors
            hover:border-primary-500 hover:bg-primary-50"
        wire:click="$wire.{{ $name }}.selectFiles()"
    >
        <input
            type="file"
            {{ $name }}="{{ $name }}"
            {{ $accept ? 'accept="' . $accept . '"' : '' }}
            {{ $multiple ? 'multiple' : '' }}
            {{ $required ? 'required' : '' }}
            class="hidden"
            wire:model="{{ $name }}"
        >
        
        <div class="upload-icon mx-auto mb-4">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </svg>
        </div>
        
        <p class="text-sm text-gray-600 mb-2">
            {{ __('Trascina i file qui o') }}
            <span class="text-primary-600 font-medium">
                {{ __('clicca per selezionare') }}
            </span>
        </p>
        
        <p class="text-xs text-gray-400">
            {{ __('Dimensione massima') }}: {{ number_format($maxSize) }} KB
            @if(!$multiple)
                ({{ __('1 file') }})
            @else
                ({{ __('File multipli') }})
            @endif
        </p>
    </div>
    
    @if($helpText)
        <p class="mt-2 text-xs text-gray-500">{{ $helpText }}</p>
    @endif
    
    <!-- File List -->
    @if(isset($$name) && is_array($$name) && count($$name) > 0)
        <div class="mt-4 space-y-2">
            @foreach($$name as $index => $file)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-sm text-gray-700">
                            {{ is_string($file) ? $file : $file->getClientOriginalName() }}
                        </span>
                    </div>
                    <button 
                        type="button"
                        wire:click="$wire.removeFile('{{ $name }}', {{ $index }})"
                        class="text-red-500 hover:text-red-700 transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.upload-zone:hover .upload-icon svg {
    color: theme('colors.primary.600');
}

.form-upload input[type="file"] {
    pointer-events: none;
}
</style>