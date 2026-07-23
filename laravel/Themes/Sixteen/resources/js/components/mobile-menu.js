/**
 * Mobile Menu Component
 * 
 * Alpine.js component for managing responsive mobile navigation
 * Provides toggle, open, close with responsive breakpoint awareness
 * 
 * Usage in Blade:
 * <div x-data="mobileMenu()" class="header-nav">
 *   <button @click="toggle()" class="navbar-toggle" x-show="isMobile()">
 *     <span>☰</span>
 *   </button>
 *   <nav x-show="isOpen || !isMobile()" x-transition class="navbar-menu">
 *     <!-- menu items -->
 *   </nav>
 * </div>
 */

export function mobileMenu() {
  return {
    isOpen: false,
    isMobileBreakpoint: false,
    
    init() {
      // Check initial breakpoint
      this.checkBreakpoint();
      
      // Listen for window resize
      window.addEventListener('resize', () => {
        this.checkBreakpoint();
      });
    },
    
    toggle() {
      this.isOpen = !this.isOpen;
    },
    
    open() {
      this.isOpen = true;
    },
    
    close() {
      this.isOpen = false;
    },
    
    checkBreakpoint() {
      // Tailwind breakpoint: md = 768px
      this.isMobileBreakpoint = window.innerWidth < 768;
      
      // Auto-close menu when resizing to desktop
      if (!this.isMobileBreakpoint && this.isOpen) {
        this.close();
      }
    },
    
    isMobile() {
      return this.isMobileBreakpoint;
    },
    
    closeOnItemClick() {
      // Close menu when item is clicked
      if (this.isMobileBreakpoint) {
        this.close();
      }
    },
    
    handleEscape(event) {
      if (event.key === 'Escape' && this.isOpen) {
        this.close();
      }
    }
  }
}
