<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 flex items-center justify-center p-4 relative overflow-hidden">
        <!-- Elegant Pastel Swallows Background -->
        <div class="absolute inset-0">
            <!-- Swallow 1 - Elegant Pastel Blue -->
            <svg class="absolute w-8 h-5 text-sky-300/50 animate-swallow-1 swallow-track" data-swallow="1" viewBox="0 0 100 60">
                <!-- Body -->
                <ellipse cx="70" cy="30" rx="6" ry="2" fill="currentColor">
                    <animate attributeName="cy" values="30;28;30" dur="1.2s" repeatCount="indefinite"/>
                </ellipse>
                <!-- Wings -->
                <path d="M45,30 Q35,18 25,30 Q35,42 45,30" fill="currentColor">
                    <animate attributeName="d" 
                        values="M45,30 Q35,18 25,30 Q35,42 45,30;M45,30 Q35,22 25,30 Q35,38 45,30;M45,30 Q35,18 25,30 Q35,42 45,30" 
                        dur="0.6s" repeatCount="indefinite"/>
                </path>
                <path d="M55,30 Q65,18 75,30 Q65,42 55,30" fill="currentColor">
                    <animate attributeName="d" 
                        values="M55,30 Q65,18 75,30 Q65,42 55,30;M55,30 Q65,22 75,30 Q65,38 55,30;M55,30 Q65,18 75,30 Q65,42 55,30" 
                        dur="0.6s" repeatCount="indefinite"/>
                </path>
                <!-- Tail -->
                <path d="M25,30 L18,26 L18,34 Z" fill="currentColor">
                    <animate attributeName="d" 
                        values="M25,30 L18,26 L18,34 Z;M25,30 L20,28 L20,32 Z;M25,30 L18,26 L18,34 Z" 
                        dur="0.8s" repeatCount="indefinite"/>
                </path>
                <!-- Head -->
                <circle cx="75" cy="30" r="2.2" fill="currentColor">
                    <animate attributeName="cy" values="30;28;30" dur="1.2s" repeatCount="indefinite"/>
                </circle>
            </svg>
            
            <!-- Swallow 2 - Elegant Pastel Cyan -->
            <svg class="absolute w-6 h-4 text-cyan-300/45 animate-swallow-2 swallow-track" data-swallow="2" viewBox="0 0 100 60">
                <!-- Body -->
                <ellipse cx="65" cy="25" rx="4.5" ry="1.8" fill="currentColor">
                    <animate attributeName="cy" values="25;23;25" dur="1.4s" repeatCount="indefinite"/>
                </ellipse>
                <!-- Wings -->
                <path d="M40,25 Q30,15 20,25 Q30,35 40,25" fill="currentColor">
                    <animate attributeName="d" 
                        values="M40,25 Q30,15 20,25 Q30,35 40,25;M40,25 Q30,18 20,25 Q30,32 40,25;M40,25 Q30,15 20,25 Q30,35 40,25" 
                        dur="0.7s" repeatCount="indefinite"/>
                </path>
                <path d="M50,25 Q60,15 70,25 Q60,35 50,25" fill="currentColor">
                    <animate attributeName="d" 
                        values="M50,25 Q60,15 70,25 Q60,35 50,25;M50,25 Q60,18 70,25 Q60,32 50,25;M50,25 Q60,15 70,25 Q60,35 50,25" 
                        dur="0.7s" repeatCount="indefinite"/>
                </path>
                <!-- Tail -->
                <path d="M20,25 L14,21 L14,29 Z" fill="currentColor">
                    <animate attributeName="d" 
                        values="M20,25 L14,21 L14,29 Z;M20,25 L16,23 L16,27 Z;M20,25 L14,21 L14,29 Z" 
                        dur="0.9s" repeatCount="indefinite"/>
                </path>
                <!-- Head -->
                <circle cx="70" cy="25" r="1.8" fill="currentColor">
                    <animate attributeName="cy" values="25;23;25" dur="1.4s" repeatCount="indefinite"/>
                </circle>
            </svg>
            
            <!-- Swallow 3 - Elegant Pastel Blue -->
            <svg class="absolute w-7 h-4 text-blue-200/40 animate-swallow-3 swallow-track" data-swallow="3" viewBox="0 0 100 60">
                <!-- Body -->
                <ellipse cx="68" cy="35" rx="5" ry="2.2" fill="currentColor">
                    <animate attributeName="cy" values="35;33;35" dur="1.1s" repeatCount="indefinite"/>
                </ellipse>
                <!-- Wings -->
                <path d="M43,35 Q33,25 23,35 Q33,45 43,35" fill="currentColor">
                    <animate attributeName="d" 
                        values="M43,35 Q33,25 23,35 Q33,45 43,35;M43,35 Q33,28 23,35 Q33,42 43,35;M43,35 Q33,25 23,35 Q33,45 43,35" 
                        dur="0.65s" repeatCount="indefinite"/>
                </path>
                <path d="M53,35 Q63,25 73,35 Q63,45 53,35" fill="currentColor">
                    <animate attributeName="d" 
                        values="M53,35 Q63,25 73,35 Q63,45 53,35;M53,35 Q63,28 73,35 Q63,42 53,35;M53,35 Q63,25 73,35 Q63,45 53,35" 
                        dur="0.65s" repeatCount="indefinite"/>
                </path>
                <!-- Tail -->
                <path d="M23,35 L17,31 L17,39 Z" fill="currentColor">
                    <animate attributeName="d" 
                        values="M23,35 L17,31 L17,39 Z;M23,35 L19,33 L19,37 Z;M23,35 L17,31 L17,39 Z" 
                        dur="0.85s" repeatCount="indefinite"/>
                </path>
                <!-- Head -->
                <circle cx="73" cy="35" r="2" fill="currentColor">
                    <animate attributeName="cy" values="35;33;35" dur="1.1s" repeatCount="indefinite"/>
                </circle>
            </svg>
            
            <!-- Swallow 4 - Elegant Pastel Azure -->
            <svg class="absolute w-5 h-3 text-azure-300/35 animate-swallow-4 swallow-track" data-swallow="4" viewBox="0 0 100 60">
                <!-- Body -->
                <ellipse cx="60" cy="20" rx="3.5" ry="1.5" fill="currentColor">
                    <animate attributeName="cy" values="20;18;20" dur="1.3s" repeatCount="indefinite"/>
                </ellipse>
                <!-- Wings -->
                <path d="M35,20 Q25,12 15,20 Q25,28 35,20" fill="currentColor">
                    <animate attributeName="d" 
                        values="M35,20 Q25,12 15,20 Q25,28 35,20;M35,20 Q25,15 15,20 Q25,25 35,20;M35,20 Q25,12 15,20 Q25,28 35,20" 
                        dur="0.75s" repeatCount="indefinite"/>
                </path>
                <path d="M45,20 Q55,12 65,20 Q55,28 45,20" fill="currentColor">
                    <animate attributeName="d" 
                        values="M45,20 Q55,12 65,20 Q55,28 45,20;M45,20 Q55,15 65,20 Q55,25 45,20;M45,20 Q55,12 65,20 Q55,28 45,20" 
                        dur="0.75s" repeatCount="indefinite"/>
                </path>
                <!-- Tail -->
                <path d="M15,20 L10,17 L10,23 Z" fill="currentColor">
                    <animate attributeName="d" 
                        values="M15,20 L10,17 L10,23 Z;M15,20 L12,18 L12,22 Z;M15,20 L10,17 L10,23 Z" 
                        dur="0.95s" repeatCount="indefinite"/>
                </path>
                <!-- Head -->
                <circle cx="65" cy="20" r="1.5" fill="currentColor">
                    <animate attributeName="cy" values="20;18;20" dur="1.3s" repeatCount="indefinite"/>
                </circle>
            </svg>
            
            <!-- Swallow 5 - Elegant Pastel Sky -->
            <svg class="absolute w-9 h-5 text-sky-200/30 animate-swallow-5 swallow-track" data-swallow="5" viewBox="0 0 100 60">
                <!-- Body -->
                <ellipse cx="72" cy="40" rx="7" ry="2.8" fill="currentColor">
                    <animate attributeName="cy" values="40;38;40" dur="1s" repeatCount="indefinite"/>
                </ellipse>
                <!-- Wings -->
                <path d="M47,40 Q37,28 27,40 Q37,52 47,40" fill="currentColor">
                    <animate attributeName="d" 
                        values="M47,40 Q37,28 27,40 Q37,52 47,40;M47,40 Q37,32 27,40 Q37,48 47,40;M47,40 Q37,28 27,40 Q37,52 47,40" 
                        dur="0.55s" repeatCount="indefinite"/>
                </path>
                <path d="M57,40 Q67,28 77,40 Q67,52 57,40" fill="currentColor">
                    <animate attributeName="d" 
                        values="M57,40 Q67,28 77,40 Q67,52 57,40;M57,40 Q67,32 77,40 Q67,48 57,40;M57,40 Q67,28 77,40 Q67,52 57,40" 
                        dur="0.55s" repeatCount="indefinite"/>
                </path>
                <!-- Tail -->
                <path d="M27,40 L20,36 L20,44 Z" fill="currentColor">
                    <animate attributeName="d" 
                        values="M27,40 L20,36 L20,44 Z;M27,40 L23,38 L23,42 Z;M27,40 L20,36 L20,44 Z" 
                        dur="0.75s" repeatCount="indefinite"/>
                </path>
                <!-- Head -->
                <circle cx="77" cy="40" r="2.5" fill="currentColor">
                    <animate attributeName="cy" values="40;38;40" dur="1s" repeatCount="indefinite"/>
                </circle>
            </svg>
        </div>
        
        <div class="relative z-10 w-full max-w-md">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-lg mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    {{ __('auth.welcome_back') }}
                </h1>
                <p class="text-gray-600 text-sm">
                    {{ __('auth.enter_credentials') }}
                </p>
            </div>

            <!-- Login Card -->
            <div class="bg-white/80 backdrop-blur-sm border border-white/20 rounded-2xl shadow-xl p-8">
                @livewire(\Modules\User\Filament\Widgets\Auth\LoginWidget::class)
                
                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white/80 text-gray-500">{{ __('auth.or_continue_with') }}</span>
                    </div>
                </div>

                <!-- Social Login -->
                <div class="grid grid-cols-2 gap-3">
                    <button class="flex items-center justify-center px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-100 hover:border-gray-300 transition-all duration-200 group">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span class="text-sm font-medium">Google</span>
                    </button>
                    <button class="flex items-center justify-center px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-100 hover:border-gray-300 transition-all duration-200 group">
                        <svg class="w-5 h-5 mr-2" fill="#1DA1F2" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                        <span class="text-sm font-medium">Twitter</span>
                    </button>
                </div>

                <!-- Sign Up Link -->
                <div class="text-center mt-6">
                    <p class="text-gray-600 text-sm">
                        {{ __('auth.no_account') }}
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-medium transition-colors duration-200">
                            {{ __('auth.create_account') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Smooth transitions */
        * {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.3);
        }

        /* Focus styles */
        input:focus, button:focus {
            outline: none;
        }

        /* Glassmorphism effect */
        .backdrop-blur-sm {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        /* Elegant pastel swallow flight animations */
        @keyframes swallow-1 {
            0% { 
                transform: translateX(-100px) translateY(40px) scale(0.8) rotate(-2deg); 
                opacity: 0;
            }
            8% { 
                opacity: 1;
            }
            30% { 
                transform: translateX(30vw) translateY(35px) scale(0.9) rotate(1deg);
            }
            60% { 
                transform: translateX(60vw) translateY(45px) scale(1) rotate(-1deg);
            }
            85% { 
                transform: translateX(85vw) translateY(40px) scale(0.9) rotate(1deg);
            }
            92% { 
                opacity: 1;
            }
            100% { 
                transform: translateX(calc(100vw + 100px)) translateY(50px) scale(0.8) rotate(-1deg); 
                opacity: 0;
            }
        }

        @keyframes swallow-2 {
            0% { 
                transform: translateX(-120px) translateY(60px) scale(0.7) rotate(1deg); 
                opacity: 0;
            }
            12% { 
                opacity: 1;
            }
            35% { 
                transform: translateX(35vw) translateY(55px) scale(0.8) rotate(-1deg);
            }
            65% { 
                transform: translateX(65vw) translateY(65px) scale(0.9) rotate(1deg);
            }
            80% { 
                transform: translateX(80vw) translateY(60px) scale(0.8) rotate(-1deg);
            }
            88% { 
                opacity: 1;
            }
            100% { 
                transform: translateX(calc(100vw + 120px)) translateY(70px) scale(0.7) rotate(1deg); 
                opacity: 0;
            }
        }

        @keyframes swallow-3 {
            0% { 
                transform: translateX(-80px) translateY(80px) scale(0.6) rotate(-1deg); 
                opacity: 0;
            }
            15% { 
                opacity: 1;
            }
            40% { 
                transform: translateX(40vw) translateY(75px) scale(0.7) rotate(1deg);
            }
            70% { 
                transform: translateX(70vw) translateY(85px) scale(0.8) rotate(-1deg);
            }
            85% { 
                transform: translateX(85vw) translateY(80px) scale(0.7) rotate(1deg);
            }
            95% { 
                opacity: 1;
            }
            100% { 
                transform: translateX(calc(100vw + 80px)) translateY(90px) scale(0.6) rotate(-1deg); 
                opacity: 0;
            }
        }

        @keyframes swallow-4 {
            0% { 
                transform: translateX(-90px) translateY(100px) scale(0.5) rotate(1deg); 
                opacity: 0;
            }
            18% { 
                opacity: 1;
            }
            45% { 
                transform: translateX(45vw) translateY(95px) scale(0.6) rotate(-1deg);
            }
            75% { 
                transform: translateX(75vw) translateY(105px) scale(0.7) rotate(1deg);
            }
            90% { 
                transform: translateX(90vw) translateY(100px) scale(0.6) rotate(-1deg);
            }
            98% { 
                opacity: 1;
            }
            100% { 
                transform: translateX(calc(100vw + 90px)) translateY(110px) scale(0.5) rotate(1deg); 
                opacity: 0;
            }
        }

        @keyframes swallow-5 {
            0% { 
                transform: translateX(-110px) translateY(70px) scale(0.7) rotate(-1deg); 
                opacity: 0;
            }
            10% { 
                opacity: 1;
            }
            50% { 
                transform: translateX(50vw) translateY(65px) scale(0.8) rotate(1deg);
            }
            80% { 
                transform: translateX(80vw) translateY(75px) scale(0.9) rotate(-1deg);
            }
            90% { 
                transform: translateX(90vw) translateY(70px) scale(0.8) rotate(1deg);
            }
            95% { 
                opacity: 1;
            }
            100% { 
                transform: translateX(calc(100vw + 110px)) translateY(80px) scale(0.7) rotate(-1deg); 
                opacity: 0;
            }
        }

        .animate-swallow-1 {
            animation: swallow-1 25s ease-in-out infinite;
            animation-delay: 0s;
        }

        .animate-swallow-2 {
            animation: swallow-2 30s ease-in-out infinite;
            animation-delay: 5s;
        }

        .animate-swallow-3 {
            animation: swallow-3 28s ease-in-out infinite;
            animation-delay: 10s;
        }

        .animate-swallow-4 {
            animation: swallow-4 35s ease-in-out infinite;
            animation-delay: 15s;
        }

        .animate-swallow-5 {
            animation: swallow-5 32s ease-in-out infinite;
            animation-delay: 20s;
        }

        /* Mouse tracking effect */
        .swallow-track {
            transition: transform 0.3s ease-out;
        }

        .swallow-track:hover {
            transform: scale(1.1);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const swallows = document.querySelectorAll('.swallow-track');
            let mouseX = 0;
            let mouseY = 0;
            let isMouseMoving = false;
            let mouseTimeout;

            // Track mouse movement
            document.addEventListener('mousemove', function(e) {
                mouseX = e.clientX;
                mouseY = e.clientY;
                isMouseMoving = true;
                
                // Clear previous timeout
                clearTimeout(mouseTimeout);
                
                // Set timeout to stop tracking after mouse stops moving
                mouseTimeout = setTimeout(() => {
                    isMouseMoving = false;
                    // Reset all swallows to their original positions
                    swallows.forEach(swallow => {
                        swallow.style.transform = '';
                    });
                }, 1000);
            });

            // Animate swallows following mouse
            function animateSwallows() {
                if (isMouseMoving) {
                    swallows.forEach((swallow, index) => {
                        const rect = swallow.getBoundingClientRect();
                        const swallowCenterX = rect.left + rect.width / 2;
                        const swallowCenterY = rect.top + rect.height / 2;
                        
                        // Calculate distance from mouse
                        const deltaX = mouseX - swallowCenterX;
                        const deltaY = mouseY - swallowCenterY;
                        const distance = Math.sqrt(deltaX * deltaX + deltaY * deltaY);
                        
                        // Only move swallows if they're within a certain range
                        if (distance < 200) {
                            // Calculate movement based on distance and swallow index
                            const moveX = (deltaX * 0.1) / (index + 1);
                            const moveY = (deltaY * 0.08) / (index + 1);
                            
                            // Apply subtle movement
                            swallow.style.transform = `translate(${moveX}px, ${moveY}px) scale(1.05)`;
                        } else {
                            // Gradually return to original position
                            swallow.style.transform = '';
                        }
                    });
                }
                
                requestAnimationFrame(animateSwallows);
            }

            // Start animation
            animateSwallows();

            // Add hover effect for individual swallows
            swallows.forEach(swallow => {
                swallow.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.2)';
                    this.style.opacity = '0.8';
                });
                
                swallow.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                    this.style.opacity = '';
                });
            });
        });
    </script>
</x-layouts.app>
