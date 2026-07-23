{{-- 
/**
 * Notifiche Component - Bootstrap Italia Compliant
 * 
 * Notifications to draw attention to brief status messages
 * Can be composed of title only or title with icon and content
 * 
 * @param string $id Unique ID for the notification
 * @param string $title Notification title
 * @param string $message Optional notification message content
 * @param string $type Notification type: 'standard', 'success', 'error', 'info', 'warning'
 * @param bool $withIcon Whether to show an icon
 * @param string $customIcon Custom icon name (overrides type-based icon)
 * @param bool $dismissible Whether the notification can be dismissed
 * @param string $position Position variant: 'standard', 'top-fix', 'bottom-fix', 'right-fix'
 * @param bool $autoHide Whether to auto-hide the notification
 * @param int $delay Auto-hide delay in milliseconds (default: 5000)
 * @param string $role ARIA role (default: 'alert')
 */
--}}

@props([
    'id' => 'notification-' . uniqid(),
    'title' => '',
    'message' => '',
    'type' => 'standard', // 'standard', 'success', 'error', 'info', 'warning'
    'withIcon' => true,
    'customIcon' => '',
    'dismissible' => false,
    'position' => 'standard', // 'standard', 'top-fix', 'bottom-fix', 'right-fix'
    'autoHide' => false,
    'delay' => 5000,
    'role' => 'alert'
])

@php
    $notificationClasses = collect(['notification']);
    
    // Add state classes
    switch ($type) {
        case 'success':
            $notificationClasses->push('success');
            $defaultIcon = 'it-check-circle';
            break;
        case 'error':
            $notificationClasses->push('error');
            $defaultIcon = 'it-close-circle';
            break;
        case 'info':
            $notificationClasses->push('info');
            $defaultIcon = 'it-info-circle';
            break;
        case 'warning':
            $notificationClasses->push('warning');
            $defaultIcon = 'it-error';
            break;
        default:
            $defaultIcon = 'it-info-circle';
            break;
    }
    
    // Add icon class if needed
    if ($withIcon) {
        $notificationClasses->push('with-icon');
    }
    
    // Add position classes
    switch ($position) {
        case 'top-fix':
            $notificationClasses->push('top-fix');
            break;
        case 'bottom-fix':
            $notificationClasses->push('bottom-fix');
            break;
        case 'right-fix':
            $notificationClasses->push('right-fix');
            break;
    }
    
    // Auto-hide functionality
    $autoHideScript = $autoHide ? "setTimeout(function() { document.getElementById('{$id}').style.display = 'none'; }, {$delay});" : '';
    
    // Final icon to use
    $iconToUse = $customIcon ?: $defaultIcon;
    
    // Title ID for ARIA labelling
    $titleId = $id . '-title';
@endphp

<div 
    class="{{ $notificationClasses->implode(' ') }}" 
    role="{{ $role }}"
    aria-labelledby="{{ $titleId }}"
    id="{{ $id }}"
    {{ $attributes }}
    @if($autoHide)
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, {{ $delay }})"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform translate-x-0"
        x-transition:leave-end="opacity-0 transform translate-x-full"
    @endif
>
    @if($title)
        <h2 id="{{ $titleId }}" class="h5">
            @if($withIcon && $iconToUse)
                <svg class="icon" aria-hidden="true">
                    <use href="#{{ $iconToUse }}"></use>
                </svg>
            @endif
            {{ $title }}
        </h2>
    @endif
    
    @if($message)
        <p>{{ $message }}</p>
    @endif
    
    @if($slot->isNotEmpty())
        {{ $slot }}
    @endif
    
    @if($dismissible)
        <button 
            type="button" 
            class="btn-close" 
            aria-label="Chiudi notifica"
            onclick="document.getElementById('{{ $id }}').style.display = 'none'"
        ></button>
    @endif
</div>

@if($autoHide && !request()->hasHeader('X-Livewire'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            {{ $autoHideScript }}
        });
    </script>
@endif

{{-- 
Usage Examples:

1. Basic notification:
<x-pub_theme::notifiche 
    title="Notification standard" />

2. Success notification with message:
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
    title="Notification standard" />

2. Success notification with message:
<x-pub_theme::notifiche 
    title="Operazione completata"
    message="I dati sono stati salvati correttamente."
    type="success" />

3. Error notification:
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
    title="Errore durante il salvataggio"
    message="Si è verificato un errore imprevisto. Riprova più tardi."
    type="error" />

4. Info notification:
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
    title="Informazione importante"
    message="Il sistema sarà in manutenzione dalle 02:00 alle 04:00."
    type="info" />

5. Warning notification:
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
    title="Attenzione"
    message="La sessione scadrà tra 5 minuti."
    type="warning" />

6. Dismissible notification:
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
    title="Notifica eliminabile"
    message="Puoi chiudere questa notifica cliccando sulla X."
    type="info"
    :dismissible="true" />

7. Auto-hide notification:
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
    title="Notifica temporanea"
    message="Questa notifica scomparirà automaticamente dopo 3 secondi."
    type="success"
    :auto-hide="true"
    :delay="3000" />

8. Fixed position notifications:
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
    title="Notifica fissa in alto"
    message="Questa notifica è posizionata in alto nella pagina."
    type="info"
    position="top-fix" />

<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
    title="Notifica fissa in basso"
    message="Questa notifica è posizionata in basso nella pagina."
    type="warning"
    position="bottom-fix" />

<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
    title="Notifica fissa a destra"
    message="Questa notifica è posizionata a destra nella pagina."
    type="success"
    position="right-fix" />

9. Notification without icon:
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
    title="Notifica senza icona"
    message="Questa notifica non ha l'icona."
    :with-icon="false" />

10. Custom icon notification:
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
    title="Notifica con icona personalizzata"
    message="Questa notifica usa un'icona personalizzata."
    type="info"
    custom-icon="it-star-outline" />

11. Complex notification with slot content:
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
    title="Aggiornamento disponibile"
    type="info"
    :dismissible="true">
    <p>È disponibile una nuova versione dell'applicazione.</p>
    <div class="mt-3">
        <button class="btn btn-primary btn-sm me-2">Aggiorna ora</button>
        <button class="btn btn-outline-secondary btn-sm">Ricorda più tardi</button>
    </div>
</x-pub_theme::notifiche>

12. Form validation notifications:
<x-pub_theme::notifiche 
</x-pub_theme::notifiche>

12. Form validation notifications:
<x-pub_theme::notifiche 
    title="Errori di validazione"
    type="error"
    :dismissible="true">
    <ul class="mb-0">
        <li>Il campo email è obbligatorio</li>
        <li>La password deve essere di almeno 8 caratteri</li>
        <li>I termini e condizioni devono essere accettati</li>
    </ul>
</x-pub_theme::notifiche>

13. System status notifications:
<x-pub_theme::notifiche 
</x-pub_theme::notifiche>

13. System status notifications:
<x-pub_theme::notifiche 
    title="Sistema operativo"
    message="Tutti i servizi funzionano correttamente."
    type="success"
    position="top-fix" />

14. Data processing notifications:
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
<x-pub_theme::notifiche 
    title="Elaborazione in corso"
    type="info"
    :auto-hide="false">
    <div class="d-flex align-items-center">
        <div class="progress-spinner progress-spinner-active size-sm me-3">
            <span class="visually-hidden">Caricamento...</span>
        </div>
        <span>I tuoi dati sono in fase di elaborazione...</span>
    </div>
</x-pub_theme::notifiche>

15. Multiple notifications stack:
<div class="notifications-container">
    <x-pub_theme::notifiche 
</x-pub_theme::notifiche>

15. Multiple notifications stack:
<div class="notifications-container">
    <x-pub_theme::notifiche 
        title="Messaggio 1"
        type="success"
        :dismissible="true"
        class="mb-2" />
    
    <x-pub_theme::notifiche 
    <x-pub_theme::notifiche 
    <x-pub_theme::notifiche 
        title="Messaggio 2"
        type="warning"
        :dismissible="true"
        class="mb-2" />
    
    <x-pub_theme::notifiche 
    <x-pub_theme::notifiche 
    <x-pub_theme::notifiche 
        title="Messaggio 3"
        type="info"
        :dismissible="true" />
</div>

16. Toast-like notification system with JavaScript:
<div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    <!-- Notifications will be dynamically added here -->
</div>

<script>
function showToast(title, message, type = 'info', autoHide = true) {
    const container = document.getElementById('toast-container');
    const notificationId = 'toast-' + Date.now();
    
    const notificationHtml = `
        <div class="notification with-icon ${type} mb-2" role="alert" id="${notificationId}">
            <h2 class="h5">
                <svg class="icon" aria-hidden="true">
                    <use href="#it-${getIconForType(type)}"></use>
                </svg>
                ${title}
            </h2>
            <p>${message}</p>
            <button type="button" class="btn-close" aria-label="Chiudi notifica" 
                    onclick="document.getElementById('${notificationId}').remove()"></button>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', notificationHtml);
    
    if (autoHide) {
        setTimeout(() => {
            const notification = document.getElementById(notificationId);
            if (notification) notification.remove();
        }, 5000);
    }
}

function getIconForType(type) {
    const icons = {
        'success': 'check-circle',
        'error': 'close-circle',
        'warning': 'error',
        'info': 'info-circle'
    };
    return icons[type] || 'info-circle';
}

// Usage example:
// showToast('Successo', 'Operazione completata!', 'success');
// showToast('Errore', 'Qualcosa è andato storto.', 'error');
</script>

Accessibility Features:
- Proper ARIA roles and labelling with aria-labelledby
- Screen reader compatible with semantic HTML structure
- Keyboard accessible dismiss buttons
- High contrast compliant colors
- Meaningful color coding with icons for color-blind users

Bootstrap Italia Integration:
- Uses official notification classes and structure
- Follows PA design system color schemes and typography
- Compatible with Bootstrap Italia icon set
- Supports all documented positioning and state variants

Dynamic Behavior:
- Auto-hide functionality with customizable delays
- Dismissible notifications with close buttons
- Fixed positioning for system-wide notifications
- Smooth transitions and animations
- Programmatic creation and management support
--}}
