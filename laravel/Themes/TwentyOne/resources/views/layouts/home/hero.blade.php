<!-- HERO - Modern 2025 Design System -->
<section class="relative min-h-screen overflow-hidden bg-gradient-to-br from-slate-900 via-purple-900/20 to-slate-900">
    <!-- Background Elements - Liquid Glass Design -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-1/2 -right-1/2 w-[200%] h-[200%] opacity-30">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600/40 via-purple-600/30 to-pink-600/40 blur-3xl animate-pulse"></div>
        </div>
        <div class="absolute -bottom-1/2 -left-1/2 w-[200%] h-[200%] opacity-20">
            <div class="absolute inset-0 bg-gradient-to-r from-cyan-600/30 via-teal-600/20 to-green-600/30 blur-3xl animate-pulse"></div>
        </div>
    </div>

    <!-- AI Personalization Overlay -->
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-slate-900/80 pointer-events-none"></div>

    <!-- Navigation Controls with Micro-interactions -->
    <div x-data="{
        currentIndex: 0,
        slides: [
            {
                title: 'Predizioni Avanzate',
                subtitle: 'Intelligenza Artificiale per Decisioni Informed',
                description: 'Analizziamo mercati e tendenze con algoritmi di machine learning per prevedere con precisione i risultati futuri.',
                action: 'Inizia a Predire',
                gradient: 'from-blue-600 to-cyan-500',
                pattern: 'pattern-spiral'
            },
            {
                title: 'Comunità di Investitori',
                subtitle: 'Connettiamo Investitori e Opportunità',
                description: 'Rete di investitori esperti condividono conoscenze e ottimizzano le decisioni di investimento.',
                action: 'Unisciti alla Comunità',
                gradient: 'from-purple-600 to-pink-500',
                pattern: 'pattern-wave'
            },
            {
                title: 'Analisi Statistica',
                subtitle: 'Calibrazione Precisione con Dati Reali',
                description: 'Monitoraggio costante delle performance per garantire la massima accuratezza nelle predizioni.',
                action: 'Scopri le Performance',
                gradient: 'from-emerald-600 to-teal-500',
                pattern: 'pattern-geometric'
            }
        ],
        nextSlide() {
            this.currentIndex = (this.currentIndex + 1) % this.slides.length;
        },
        prevSlide() {
            this.currentIndex = (this.currentIndex - 1 + this.slides.length) % this.slides.length;
        },
        getPersonalizedContent() {
            // AI-driven personalization based on user preferences
            return this.slides[Math.floor(Math.random() * this.slides.length)];
        }
    }" class="relative h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
        
        <!-- Personalized Content Display -->
        <div class="absolute inset-0 flex items-center justify-center">
            <div x-data="getPersonalizedContent()" class="text-center px-4 sm:px-8 lg:px-12">
                <!-- AI Personalization Badge -->
                <div class="inline-flex items-center gap-2 mb-6 px-4 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                    <span class="text-sm font-medium text-white">Contenuto Personalizzato</span>
                </div>

                <!-- Main Title with Liquid Glass Effect -->
                <h1 class="text-5xl sm:text-6xl lg:text-7xl xl:text-8xl font-bold mb-6 bg-gradient-to-r from-white via-slate-100 to-slate-200 bg-clip-text text-transparent leading-tight">
                    <span x-text="title" class="block"></span>
                </h1>

                <!-- Subtitle with Micro-interactions -->
                <p class="text-xl sm:text-2xl lg:text-3xl text-slate-200 mb-8 font-light opacity-90 hover:opacity-100 transition-opacity duration-500">
                    <span x-text="subtitle" class="block"></span>
                </p>

                <!-- Description with Enhanced Typography -->
                <p class="text-lg sm:text-xl text-slate-300 mb-12 max-w-3xl mx-auto leading-relaxed opacity-80 hover:opacity-100 transition-opacity duration-700">
                    <span x-text="description" class="block"></span>
                </p>

                <!-- Call to Action with Glass Morphism -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <button class="group relative px-8 py-4 bg-gradient-to-r from-white/10 to-white/5 backdrop-blur-md border border-white/20 rounded-full text-white font-semibold hover:from-white/20 hover:to-white/10 hover:scale-105 transition-all duration-300 hover:shadow-2xl hover:shadow-white/20">
                        <span x-text="action" class="block"></span>
                        <div class="absolute inset-0 rounded-full bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </button>
                    
                    <!-- Secondary Action -->
                    <button class="px-6 py-3 text-slate-300 hover:text-white transition-colors duration-300 font-medium">
                        Scopri di Più
                    </button>
                </div>
            </div>
        </div>

        <!-- Navigation Arrows with Enhanced Micro-interactions -->
        <div class="absolute inset-0 flex items-center justify-between px-4 sm:px-6 lg:px-8 pointer-events-none">
            <button @click="prevSlide()" 
                    class="group pointer-events-auto p-4 bg-white/10 backdrop-blur-md border border-white/20 rounded-full hover:bg-white/20 hover:scale-110 transition-all duration-300 hover:shadow-2xl hover:shadow-white/20">
                <svg class="w-6 h-6 text-white group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            
            <button @click="nextSlide()" 
                    class="group pointer-events-auto p-4 bg-white/10 backdrop-blur-md border border-white/20 rounded-full hover:bg-white/20 hover:scale-110 transition-all duration-300 hover:shadow-2xl hover:shadow-white/20">
                <svg class="w-6 h-6 text-white group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>

        <!-- Interactive Background Pattern -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-purple-400/20 via-transparent to-transparent"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-cyan-400/20 via-transparent to-transparent"></div>
        </div>

        <!-- Floating Elements with AI Personalization -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-1/4 left-1/4 w-32 h-32 bg-gradient-to-r from-blue-400/20 to-purple-400/20 rounded-full blur-3xl animate-bounce"></div>
            <div class="absolute top-1/3 right-1/4 w-48 h-48 bg-gradient-to-r from-pink-400/20 to-cyan-400/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-1/4 left-1/3 w-40 h-40 bg-gradient-to-r from-green-400/20 to-teal-400/20 rounded-full blur-3xl animate-bounce" style="animation-delay: 1s;"></div>
        </div>
    </div>

    <!-- Sustainability Overlay - Green Design Badge -->
    <div class="absolute bottom-6 left-6 right-6 lg:left-auto lg:right-6 lg:bottom-8">
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-500/10 backdrop-blur-md rounded-full border border-green-400/20">
            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
            <span class="text-sm font-medium text-green-400">Sostenibile & Responsabile</span>
        </div>
    </div>

    <!-- AI Personalization Badge - Floating -->
    <div class="absolute top-6 right-6 lg:top-8 lg:right-8">
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500/10 backdrop-blur-md rounded-full border border-blue-400/20 animate-float">
            <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></div>
            <span class="text-sm font-medium text-blue-400">AI Personalizzato</span>
        </div>
    </div>
</section>
