<div>
  <style>
      /* ANIMAZIONI SCROLLBAR */
      @keyframes scrollbarPulse {
          0%, 100% {
              background: rgba(167, 139, 250, 0.7);
              box-shadow: 0 0 5px rgba(167, 139, 250, 0.5);
          }
          50% {
              background: rgba(196, 181, 253, 0.9);
              box-shadow: 0 0 12px rgba(167, 139, 250, 0.8);
          }
      }

      @keyframes scrollbarTrackGlow {
          0% { box-shadow: inset 0 0 10px rgba(99, 102, 241, 0.1); }
          50% { box-shadow: inset 0 0 15px rgba(99, 102, 241, 0.3); }
          100% { box-shadow: inset 0 0 10px rgba(99, 102, 241, 0.1); }
      }

      /* Stili scrollbar applicati a tutti gli elementi con la classe */
      .scrollbar-custom::-webkit-scrollbar {
          width: 8px;
          height: 8px;
      }

      .scrollbar-custom::-webkit-scrollbar-thumb {
          background: rgba(167, 139, 250, 0.7);
          border-radius: 12px;
          border: 2px solid rgba(255, 255, 255, 0.15);
          animation: scrollbarPulse 3s ease-in-out infinite;
          transition: all 0.4s cubic-bezier(0.22, 0.61, 0.36, 1);
      }

      .scrollbar-custom::-webkit-scrollbar-thumb:hover {
          transform: scaleX(1.1);
          animation: 
              scrollbarPulse 1.5s ease-in-out infinite,
              pulseGlow 2s ease-in-out infinite;
      }

      .scrollbar-custom::-webkit-scrollbar-track {
          background: rgba(30, 30, 50, 0.2);
          border-radius: 12px;
          margin: 4px;
          animation: scrollbarTrackGlow 6s ease-in-out infinite;
      }

      /* ANIMAZIONI ELEMENTI */
      @keyframes pulseGlow {
          0%, 100% {
              box-shadow: 0 0 10px rgba(99, 102, 241, 0.5),
                  0 0 20px rgba(167, 139, 250, 0.4);
          }
          50% {
              box-shadow: 0 0 20px rgba(99, 102, 241, 0.8),
                  0 0 40px rgba(167, 139, 250, 0.6);
          }
      }

      .rating-item {
          scroll-margin: 12px;
          transition: all 0.4s cubic-bezier(0.33, 1, 0.68, 1);
      }

      .rating-item:hover {
          transform: translateY(-2px);
          background: rgba(255, 255, 255, 0.03) !important;
      }

      .pulse-glow {
          animation: pulseGlow 3s ease-in-out infinite;
      }
  </style>

  <script>
      // Funzione per inizializzare il comportamento di scroll
      function initScrollBehavior() {
          document.querySelectorAll('.ratings-container').forEach(container => {
              const items = container.querySelectorAll('.rating-item');
              
              items.forEach(item => {
                  item.addEventListener('mouseenter', function() {
                      // Calcolo posizione per centrare l'elemento
                      const scrollPosition = this.offsetTop - container.offsetTop - 
                                          (container.clientHeight / 2) + 
                                          (this.clientHeight / 2);
                      
                      // Scroll animato
                      container.scrollTo({
                          top: scrollPosition,
                          behavior: 'smooth'
                      });
                      
                      // Highlight
                      this.classList.add('ring-2', 'ring-indigo-400');
                  });

                  item.addEventListener('mouseleave', function() {
                      this.classList.remove('ring-2', 'ring-indigo-400');
                  });
              });
          });
      }

      // Inizializzazione al caricamento della pagina
      document.addEventListener('DOMContentLoaded', initScrollBehavior);

      // Se stai usando Livewire, aggiungi anche questo
      document.addEventListener('livewire:load', initScrollBehavior);
      document.addEventListener('livewire:update', initScrollBehavior);
  </script>

  <article class="bg-gray-900/50 backdrop-blur-lg rounded-xl flex flex-col p-4 shadow-inner scrollbar-custom" 
           style="max-height: 160px; overflow: auto;">
      @if (count($ratings) === 1)
          <div class="flex flex-col gap-2 max-h-[160px] overflow-y-auto pr-2 scrollbar-custom">
              <button type="button" wire:click="openBetModal('{{ $ratings[0]['id'] }}')"
                  class="w-full bg-emerald-500 text-white px-4 py-2 rounded-md transition hover:opacity-80 hover:scale-105 pulse-glow">
                  Yes
              </button>
              <button type="button" wire:click="openBetModal('{{ $ratings[0]['id'] }}')"
                  class="w-full bg-sky-500 text-white px-4 py-2 rounded-md transition hover:opacity-80 hover:scale-105 pulse-glow">
                  No
              </button>
          </div>
      @else
          <div class="ratings-container flex flex-col gap-3 max-h-[160px] overflow-y-auto pr-2 scrollbar-custom">
              @foreach ($ratings as $rating)
                  <div id="rating-{{ $rating['id'] }}" 
                       class="rating-item flex items-center mb-1 group relative p-2 rounded-lg transition-all duration-300">
                      <button type="button" wire:click="openBetModal('{{ $rating['id'] }}')"
                          wire:loading.class="opacity-50" wire:target="openBetModal('{{ $rating['id'] }}')"
                          class="relative w-full flex items-center px-4 py-2 rounded-lg overflow-hidden 
                              bg-white/10 backdrop-blur border border-white/10 text-gray-100 font-semibold 
                              shadow-inner transition hover:scale-[1.02] hover:shadow-lg">
                          
                          <span class="absolute left-0 top-0 h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-amber-400 transition-all duration-500 pulse-glow"
                              style="width: {{ $ratings_percentage[$rating['id']] }}%; z-index: 1;"></span>

                          <span class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-10 transition"></span>

                          <span class="relative z-10">{{ $rating['title'] }}</span>
                      </button>

                      <div class="relative ml-auto z-10 text-right font-bold min-w-[80px] flex items-center justify-end text-indigo-300">
                          <span class="block">{{ $ratings_percentage[$rating['id']] }}%</span>

                          @if (isset($rating['pivot']['yes_or_no']) && $rating['pivot']['yes_or_no'])
                              <div class="absolute right-0 hidden space-x-1 group-hover:flex bg-gray-900/80 px-1 py-0.5 rounded shadow">
                                  <button type="button"
                                      class="px-2 py-1 text-xs bg-emerald-500 text-white rounded transition hover:scale-105 pulse-glow">Yes</button>
                                  <button type="button"
                                      class="px-2 py-1 text-xs bg-sky-500 text-white rounded transition hover:scale-105 pulse-glow">No</button>
                              </div>
                          @endif
                      </div>
                  </div>
              @endforeach
          </div>
      @endif
  </article>

  <!-- Modals -->
  @foreach ($ratings as $rating)
      <x-filament::modal id="modal-rating-{{ $rating['id'] }}" :close-button="true" :close-by-clicking-away="true">
          <x-slot name="heading">{{ $article->title }}</x-slot>
          <!-- ... resto del modal ... -->
      </x-filament::modal>
  @endforeach
</div>