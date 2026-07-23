/**
 * Dropdown Toggle Component
 * 
 * Alpine.js component for managing dropdown menus
 * Provides toggle, open, close, and click-away handling
 * 
 * Usage in Blade:
 * <div x-data="dropdownToggle()" @click.away="close()">
 *   <button @click="toggle()" class="dropdown-toggle">Menu</button>
 *   <div x-show="isOpen" x-transition class="dropdown-menu">
 *     <a href="#" @click.prevent="selectItem()">Item 1</a>
 *   </div>
 * </div>
 */

export function dropdownToggle() {
  return {
    isOpen: false,
    
    init() {
      // Initialize dropdown
      // Can add any setup logic here
    },
    
    toggle() {
      this.isOpen = !this.isOpen;
    },
    
    open() {
      this.isOpen = true;
      this.focusButton();
    },
    
    close() {
      this.isOpen = false;
    },
    
    selectItem(callback = null) {
      if (callback) {
        callback();
      }
      this.close();
    },
    
    focusButton() {
      // Focus management could go here
      // this.$refs.button?.focus();
    },
    
    handleKeydown(event) {
      if (event.key === 'Escape') {
        this.close();
      }
      if (event.key === 'ArrowDown') {
        event.preventDefault();
        this.open();
      }
    }
  }
}
