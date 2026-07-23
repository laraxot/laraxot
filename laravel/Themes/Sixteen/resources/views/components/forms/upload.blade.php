{{-- 
/**
 * Upload Component - Bootstrap Italia Compliant
 * 
 * Form elements dedicated to file uploads with drag & drop functionality
 * Supports various upload states and visual feedback
 * 
 * @param string $name Form field name
 * @param string $id Unique ID for the input element
 * @param string $label Upload area label/heading
 * @param string $description Upload description text
 * @param string $fileInfo File type and size info (e.g., "PDF (3.7MB)")
 * @param string $dragText Main drag & drop text
 * @param string $selectText Select file link text
 * @param string $accept Accepted file types (e.g., ".pdf,.doc,.docx")
 * @param bool $multiple Whether multiple files are allowed
 * @param bool $required Whether upload is required
 * @param string $state Upload state: 'default', 'loading', 'success'
 * @param string $loadingText Text shown during loading
 * @param string $successText Text shown on success
 * @param string $iconPath Path to upload icon image
 * @param string $progressValue Progress percentage (0-100)
 */
--}}

@props([
    'name' => 'upload',
    'id' => 'upload-' . uniqid(),
    'label' => '',
    'description' => '',
    'fileInfo' => '',
    'dragText' => 'Trascina il file per caricarlo',
    'selectText' => 'selezionalo dal dispositivo',
    'accept' => '',
    'multiple' => false,
    'required' => false,
    'state' => 'default', // 'default', 'loading', 'success'
    'loadingText' => 'Caricamento in corso...',
    'successText' => 'Caricamento completato',
    'iconPath' => '/assets/upload-drag-drop-icon.svg',
    'progressValue' => '0'
])

@php
    $formClasses = collect(['upload-dragdrop']);
    
    // Add state classes
    switch ($state) {
        case 'loading':
            $formClasses->push('loading');
            break;
        case 'success':
            $formClasses->push('success');
            break;
        default:
            // default state - no additional class
            break;
    }
    
    $classes = $formClasses->implode(' ');
@endphp

<form 
    class="{{ $classes }}" 
    method="post" 
    enctype="multipart/form-data" 
    data-bs-upload-dragdrop
    {{ $attributes }}
>
    @csrf
    
    <div class="upload-dragdrop-image">
        <img 
            src="{{ $iconPath }}" 
            alt="Upload icon" 
            aria-hidden="true"
        >
        
        {{-- Loading state indicator --}}
        <div class="upload-dragdrop-loading">
            <div 
                class="progress-donut" 
                data-bs-progress-donut
                @if($progressValue > 0)
                data-progress="{{ $progressValue }}"
                @endif
            ></div>
        </div>
        
        {{-- Success state indicator --}}
        <div class="upload-dragdrop-success">
            <svg class="icon" aria-hidden="true">
                <use href="#it-check"></use>
            </svg>
        </div>
    </div>
    
    <div class="upload-dragdrop-text">
        @if($fileInfo)
            <p class="upload-dragdrop-weight">
                <svg class="icon icon-xs" aria-hidden="true">
                    <use href="#it-file"></use>
                </svg>
                {{ $fileInfo }}
            </p>
        @endif
        
        <h5>
            @if($state === 'loading')
                {{ $label ?: 'Nome file in caricamento' }}
            @elseif($state === 'success')
                {{ $label ?: 'Nome file caricato' }}
            @else
                {{ $label ?: $dragText }}
            @endif
        </h5>
        
        <p>
            @if($state === 'loading')
                {{ $loadingText }}
            @elseif($state === 'success')
                {{ $successText }}
            @else
                oppure 
                <input 
                    type="file" 
                    name="{{ $name }}" 
                    id="{{ $id }}" 
                    class="upload-dragdrop-input"
                    @if($accept) accept="{{ $accept }}" @endif
                    @if($multiple) multiple @endif
                    @if($required) required @endif
                />
                <label for="{{ $id }}">{{ $selectText }}</label>
                @if($description)
                    <br><small class="form-text text-muted">{{ $description }}</small>
                @endif
            @endif
        </p>
    </div>
    
    <input type="submit" value="Submit" class="d-none" />
</form>

{{-- Include JavaScript for drag & drop functionality --}}
@pushOnce('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize upload drag & drop functionality
    const uploadForms = document.querySelectorAll('[data-bs-upload-dragdrop]');
    
    uploadForms.forEach(function(form) {
        const fileInput = form.querySelector('.upload-dragdrop-input');
        
        if (!fileInput) return;
        
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            form.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });
        
        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            form.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            form.addEventListener(eventName, unhighlight, false);
        });
        
        // Handle dropped files
        form.addEventListener('drop', handleDrop, false);
        
        // Handle file input change
        fileInput.addEventListener('change', function(e) {
            handleFiles(e.target.files);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        function highlight() {
            form.classList.add('dragover');
        }
        
        function unhighlight() {
            form.classList.remove('dragover');
        }
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            handleFiles(files);
        }
        
        function handleFiles(files) {
            // Set files to input
            fileInput.files = files;
            
            // Simulate upload process
            simulateUpload(files[0]);
        }
        
        function simulateUpload(file) {
            if (!file) return;
            
            // Update file info
            const weightElement = form.querySelector('.upload-dragdrop-weight');
            if (weightElement) {
                const fileSize = (file.size / 1024 / 1024).toFixed(1);
                const fileType = file.name.split('.').pop().toUpperCase();
                weightElement.innerHTML = `
                    <svg class="icon icon-xs" aria-hidden="true">
                        <use href="#it-file"></use>
                    </svg>
                    ${fileType} (${fileSize}MB)
                `;
            }
            
            // Start loading state
            form.classList.add('loading');
            
            // Update heading
            const heading = form.querySelector('h5');
            if (heading) {
                heading.textContent = file.name;
            }
            
            // Fire custom event
            const uploadStartEvent = new CustomEvent('uploadStart', {
                detail: { file: file, form: form }
            });
            form.dispatchEvent(uploadStartEvent);
            
            // Simulate progress (this would be replaced with actual upload logic)
            simulateProgress();
        }
        
        function simulateProgress() {
            const progressDonut = form.querySelector('[data-bs-progress-donut]');
            let progress = 0;
            
            const interval = setInterval(() => {
                progress += Math.random() * 30;
                
                if (progress >= 100) {
                    progress = 100;
                    clearInterval(interval);
                    
                    // Complete upload
                    setTimeout(() => {
                        form.classList.remove('loading');
                        form.classList.add('success');
                        
                        // Update text
                        const paragraph = form.querySelector('.upload-dragdrop-text p:last-child');
                        if (paragraph) {
                            paragraph.textContent = '{{ $successText }}';
                        }
                        
                        // Fire custom event
                        const uploadCompleteEvent = new CustomEvent('uploadComplete', {
                            detail: { form: form }
                        });
                        form.dispatchEvent(uploadCompleteEvent);
                    }, 500);
                }
                
                // Update progress donut (if using actual progress donut component)
                if (progressDonut) {
                    progressDonut.setAttribute('data-progress', Math.round(progress));
                }
            }, 200);
        }
    });
});
</script>
@endPushOnce

{{-- 
Usage Examples:

1. Basic upload:
<x-pub_theme::upload 
<x-pub_theme::upload 
<x-pub_theme::upload 
    name="document"
    file-info="PDF (Max 5MB)"
    accept=".pdf"
    required />

2. Multiple file upload:
<x-pub_theme::upload 
<x-pub_theme::upload 
<x-pub_theme::upload 
    name="documents[]"
    :multiple="true"
    label="Upload documenti"
    file-info="PDF, DOC, DOCX (Max 10MB ciascuno)"
    accept=".pdf,.doc,.docx" />

3. Upload in loading state:
<x-pub_theme::upload 
<x-pub_theme::upload 
<x-pub_theme::upload 
    state="loading"
    file-info="PDF (3.7MB)"
    label="documento.pdf"
    loading-text="Caricamento in corso..." />

4. Upload in success state:
<x-pub_theme::upload 
<x-pub_theme::upload 
<x-pub_theme::upload 
    state="success"
    file-info="PDF (3.7MB)"
    label="documento.pdf"
    success-text="Caricamento completato" />

5. Custom text and description:
<x-pub_theme::upload 
<x-pub_theme::upload 
<x-pub_theme::upload 
    drag-text="Trascina qui i tuoi file"
    select-text="oppure seleziona dal computer"
    description="Formati supportati: PDF, DOC, DOCX. Dimensione massima: 10MB per file" />

6. Image upload with custom icon:
<x-pub_theme::upload 
<x-pub_theme::upload 
<x-pub_theme::upload 
    name="avatar"
    accept="image/*"
    icon-path="/assets/image-upload-icon.svg"
    file-info="JPG, PNG (Max 2MB)" />

Event Handling:
The component fires custom events that can be listened to:

// Upload start event
document.addEventListener('uploadStart', function(e) {
    console.log('Upload started:', e.detail.file);
});

// Upload complete event  
document.addEventListener('uploadComplete', function(e) {
    console.log('Upload completed');
});

Bootstrap Italia Classes Reference:
- .upload-dragdrop: Main container class
- .dragover: Applied when dragging over drop zone
- .loading: Applied during upload process
- .success: Applied when upload is successful
- .upload-dragdrop-image: Image/icon container
- .upload-dragdrop-text: Text content container
- .upload-dragdrop-input: Hidden file input
- .upload-dragdrop-weight: File size/type display
- .progress-donut: Circular progress indicator
--}}
