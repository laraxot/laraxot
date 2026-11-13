<x-layouts.main>
    <x-slot name="title">
        {{ __('pub_theme::home.meta.title.title') }} - {{ config('app.name', 'Laravel') }}
    </x-slot>
    
    <x-slot name="description">
        {{ __('pub_theme::home.meta.description.description') }}
    </x-slot>
    
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                {{ __('pub_theme::home.hero.title.title') }}
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100">
                {{ __('pub_theme::home.hero.subtitle.description') }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#features" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-colors">
                    {{ __('pub_theme::home.hero.discover.label') }}
                </a>
                <a href="#contact" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors">
                    {{ __('pub_theme::home.hero.contact.label') }}
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">
                {{ __('pub_theme::home.features.title.title') }}
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">{{ __('pub_theme::home.features.performance.title') }}</h3>
                    <p class="text-gray-600">
                        {{ __('pub_theme::home.features.performance.description') }}
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">{{ __('pub_theme::home.features.responsive.title') }}</h3>
                    <p class="text-gray-600">
                        {{ __('pub_theme::home.features.responsive.description') }}
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">{{ __('pub_theme::home.features.custom.title') }}</h3>
                    <p class="text-gray-600">
                        {{ __('pub_theme::home.features.custom.description') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold mb-6 text-gray-800">
                        {{ __('pub_theme::home.about.title.title') }}
                    </h2>
                    <p class="text-lg text-gray-600 mb-6">
                        {{ __('pub_theme::home.about.intro.description') }}
                    </p>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('pub_theme::home.benefits.design.label') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('pub_theme::home.benefits.components.label') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('pub_theme::home.benefits.docs.label') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('pub_theme::home.benefits.dark_mode.label') }}
                        </li>
                    </ul>
                </div>
                <div class="bg-gray-100 p-8 rounded-lg">
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800">{{ __('pub_theme::home.quickstart.title.title') }}</h3>
                        <p class="text-gray-600 mb-4">
                            {{ __('pub_theme::home.quickstart.description.description') }}
                        </p>
                        <code class="bg-gray-100 p-2 rounded text-sm block">
                            npm install && npm run build
                        </code>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">
                {{ __('pub_theme::home.contact.title.title') }}
            </h2>
            
            <div class="max-w-2xl mx-auto">
                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('pub_theme::home.form.name.label') }}</label>
                            <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('pub_theme::home.form.email.label') }}</label>
                            <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">{{ __('pub_theme::home.form.message.label') }}</label>
                        <textarea id="message" name="message" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                            {{ __('pub_theme::home.form.submit.label') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-layouts.main> 