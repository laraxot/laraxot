/**
 * Modal Component
 * 
 * Alpine.js component for managing modal dialogs
 * Provides open, close, and keyboard handling (Escape to close)
 * 
 * Usage in Blade:
 * <div x-data="modal()" @keydown.escape="close()">
 *   <button @click="open()" class="search-button">Search</button>
 *   <div x-show="isOpen" x-transition class="modal-backdrop" @click="close()">
 *     <div class="modal-dialog" @click.stop>
 *       <button @click="close()" class="modal-close">×</button>
 *       <!-- Modal content -->
 *     </div>
 *   </div>
 * </div>
 */

export function modal() {
  return {
    isOpen: false,
    
    init() {
      // Initialize modal
      // Add event listeners for Escape key
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && this.isOpen) {
          this.close();
        }
      });
    },
    
    open() {
      this.isOpen = true;
      this.trapFocus();
      // Optionally prevent body scroll
      document.body.style.overflow = 'hidden';
    },
    
    close() {
      this.isOpen = false;
      // Restore body scroll
      document.body.style.overflow = '';
    },
    
    toggle() {
      this.isOpen ? this.close() : this.open();
    },
    
    trapFocus() {
      // Focus management for accessibility
      // Could implement full focus trap here
      const firstFocusable = this.$el?.querySelector(
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
      );
      if (firstFocusable) {
        setTimeout(() => firstFocusable.focus(), 0);
      }
    },
    
    handleEscape(event) {
      if (event.key === 'Escape') {
        this.close();
      }
    }
  }
}
