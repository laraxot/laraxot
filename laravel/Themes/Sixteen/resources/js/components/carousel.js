/**
 * Governance Carousel Component
 * 
 * Alpine.js component for managing carousel navigation
 * Works with Splide carousel library - handles prev/next/keyboard
 * 
 * Usage in Blade:
 * <div x-data="governanceCarousel()" class="carousel-wrapper">
 *   <div id="governance-carousel" class="splide">
 *     <!-- Splide structure -->
 *   </div>
 *   <button @click="prev()" :disabled="isAtStart()">❮</button>
 *   <button @click="next()" :disabled="isAtEnd()">❯</button>
 * </div>
 */

export function governanceCarousel() {
  return {
    splide: null,
    currentIndex: 0,
    
    init() {
      // Initialize Splide carousel if it exists
      const element = document.getElementById('governance-carousel');
      
      if (element && window.Splide) {
        this.splide = new window.Splide(element, {
          type: 'slide',
          perPage: 3,
          rewind: false,
          pagination: true,
          arrows: false, // We'll use our own buttons
          breakpoints: {
            768: {
              perPage: 2
            },
            480: {
              perPage: 1
            }
          }
        });
        
        this.splide.mount();
        
        // Update current index when slide changes
        this.splide.on('moved', () => {
          this.currentIndex = this.splide.index;
        });
      }
    },
    
    prev() {
      if (this.splide) {
        this.splide.go('<');
      }
    },
    
    next() {
      if (this.splide) {
        this.splide.go('>');
      }
    },
    
    isAtStart() {
      return this.splide?.index === 0;
    },
    
    isAtEnd() {
      if (!this.splide) return false;
      const slides = this.splide.length;
      const perPage = this.splide.options.perPage;
      return this.splide.index >= slides - perPage;
    },
    
    goToSlide(index) {
      if (this.splide) {
        this.splide.go(index);
      }
    },
    
    handleKeydown(event) {
      if (event.key === 'ArrowLeft') {
        this.prev();
      }
      if (event.key === 'ArrowRight') {
        this.next();
      }
    }
  }
}
