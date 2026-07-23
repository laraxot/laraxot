{{-- Landing Page Template - AGID Compliant --}}
{{-- Template per landing page di servizi specifici --}}

@extends('layouts.marketing')

@section('title', $service->name . ' - ' . config('sixteen.app.name'))

@section('content')
    {{-- Service Hero --}}
    <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="flex items-center mb-6">
                <div class="bg-white/20 p-3 rounded-lg mr-4">
                    @if($service->icon)
                        <x-dynamic-component :component="$service->icon" class="w-8 h-8" />
                    @else
                        <x-filament::icon icon="heroicon-o-cog-6-tooth" class="w-8 h-8" />
f7ac8eda (.)
                    @endif
                </div>
                <div>
                    <nav class="text-sm text-blue-100 mb-1" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2">
                            <li><a href="{{ route('home') }}" class="hover:text-white">Home</a></li>
                            <li class="flex items-center">
                                <x-filament::icon icon="heroicon-o-chevron-right" class="w-4 h-4 mx-2" />
f7ac8eda (.)
                                <a href="{{ route('services') }}" class="hover:text-white">Servizi</a>
                            </li>
                            <li class="flex items-center">
                                <x-filament::icon icon="heroicon-o-chevron-right" class="w-4 h-4 mx-2" />
f7ac8eda (.)
                                <span class="text-white" aria-current="page">{{ $service->name }}</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="text-3xl font-bold">{{ $service->name }}</h1>
                </div>
            </div>
            
            @if($service->description)
                <p class="text-lg text-blue-100 max-w-3xl">{{ $service->description }}</p>
            @endif
        </div>
    </section>

    {{-- Service Content --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-4 gap-8">
                {{-- Main Content --}}
                <div class="lg:col-span-3">
                    {{-- Service Actions --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Accesso al Servizio</h2>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            {{-- Action Cards --}}
                            @foreach($service->actions as $action)
                                <div class="border border-gray-200 rounded-lg p-6 hover:border-blue-300 transition-colors">
                                    <div class="flex items-center mb-4">
                                        <div class="bg-blue-100 p-2 rounded-lg mr-4">
                                            <x-filament::icon icon="heroicon-o-arrow-right-on-rectangle" class="w-6 h-6 text-blue-600" />
f7ac8eda (.)
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $action['title'] }}</h3>
                                    </div>
                                    
                                    @if($action['description'])
                                        <p class="text-gray-600 mb-4">{{ $action['description'] }}</p>
                                    @endif
                                    
                                    <x-button 
                                    <x-button 
                                        href="{{ $action['url'] }}"
                                        variant="primary"
                                        size="md"
                                        class="w-full">
                                        {{ $action['button_text'] ?? 'Accedi' }}
                                    </x-button>
                                    </x-button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Service Details --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Informazioni sul Servizio</h2>
                        
                        <div class="prose prose-blue max-w-none">
                            {!! $service->content !!}
                        </div>
                    </div>

                    {{-- Requirements --}}
                    @if($service->requirements)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Requisiti e Documenti Necessari</h2>
                            
                            <div class="space-y-3">
                                @foreach($service->requirements as $requirement)
                                    <div class="flex items-start">
                                        <x-filament::icon icon="heroicon-o-check-circle" class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" />
f7ac8eda (.)
                                        <span class="text-gray-700">{{ $requirement }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- FAQ --}}
                    @if($service->faqs->isNotEmpty())
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Domande Frequenti</h2>
                            
                            <div class="space-y-4">
                                @foreach($service->faqs as $faq)
                                    <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $faq->question }}</h3>
                                        <p class="text-gray-600">{{ $faq->answer }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1">
                    {{-- Quick Info --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informazioni Rapide</h3>
                        
                        <div class="space-y-3">
                            @if($service->cost)
                                <div class="flex items-center">
                                    <x-filament::icon icon="heroicon-o-currency-euro" class="w-5 h-5 text-gray-400 mr-3" />
f7ac8eda (.)
                                    <span class="text-gray-700">{{ $service->cost }}</span>
                                </div>
                            @endif
                            
                            @if($service->processing_time)
                                <div class="flex items-center">
                                    <x-filament::icon icon="heroicon-o-clock" class="w-5 h-5 text-gray-400 mr-3" />
f7ac8eda (.)
                                    <span class="text-gray-700">{{ $service->processing_time }}</span>
                                </div>
                            @endif
                            
                            @if($service->availability)
                                <div class="flex items-center">
                                    <x-filament::icon icon="heroicon-o-globe-alt" class="w-5 h-5 text-gray-400 mr-3" />
f7ac8eda (.)
                                    <span class="text-gray-700">{{ $service->availability }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Contact Info --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Contatti</h3>
                        
                        <div class="space-y-3">
                            @if($service->contact_phone)
                                <div class="flex items-center">
                                    <x-filament::icon icon="heroicon-o-phone" class="w-5 h-5 text-gray-400 mr-3" />
f7ac8eda (.)
                                    <a href="tel:{{ $service->contact_phone }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $service->contact_phone }}
                                    </a>
                                </div>
                            @endif
                            
                            @if($service->contact_email)
                                <div class="flex items-center">
                                    <x-filament::icon icon="heroicon-o-envelope" class="w-5 h-5 text-gray-400 mr-3" />
f7ac8eda (.)
                                    <a href="mailto:{{ $service->contact_email }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $service->contact_email }}
                                    </a>
                                </div>
                            @endif
                            
                            @if($service->office_hours)
                                <div class="flex items-start">
                                    <x-filament::icon icon="heroicon-o-calendar-days" class="w-5 h-5 text-gray-400 mr-3 mt-0.5" />
f7ac8eda (.)
                                    <span class="text-gray-700">{{ $service->office_hours }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Related Services --}}
                    @if($relatedServices->isNotEmpty())
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Servizi Correlati</h3>
                            
                            <div class="space-y-2">
                                @foreach($relatedServices as $related)
                                    <a href="{{ route('services.show', $related) }}" 
                                       class="block text-blue-600 hover:text-blue-800 text-sm">
                                        {{ $related->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Feedback Section --}}
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Hai trovato utile questo servizio?</h2>
                <p class="text-gray-600 mb-6">Il tuo feedback ci aiuta a migliorare</p>
                
                <div class="flex justify-center space-x-4">
                    <x-button 
                    <x-button 
                        href="#"
                        variant="primary"
                        @click="$dispatch('open-modal', { id: 'feedback-modal' })">
                        Lascia un feedback
                    </x-button>
                    
                    <x-button 
                        href="{{ route('contact') }}"
                        variant="secondary">
                        Segnala un problema
                    </x-button>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('modals')
    {{-- Feedback Modal --}}
    <x-modal id="feedback-modal" title="Valuta il Servizio">
    <x-modal id="feedback-modal" title="Valuta il Servizio">
        <form action="{{ route('services.feedback', $service) }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Quanto è stato utile questo servizio?
                    </label>
                    <div class="flex space-x-2">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="rating" value="{{ $i }}" class="sr-only">
                                <x-filament::icon icon="heroicon-o-star" class="w-8 h-8 text-gray-300 hover:text-yellow-400 peer-checked:text-yellow-500" />
f7ac8eda (.)
                            </label>
                        @endfor
                    </div>
                </div>
                
                <div>
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                        Commento (opzionale)
                    </label>
                    <textarea 
                        id="comment" 
                        name="comment" 
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Condividi la tua esperienza..."></textarea>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <x-button 
                <x-button 
                    type="button" 
                    variant="secondary"
                    @click="$dispatch('close-modal', { id: 'feedback-modal' })">
                    Annulla
                </x-button>
                
                <x-button 
                    type="submit" 
                    variant="primary">
                    Invia Feedback
                </x-button>
            </div>
        </form>
    </x-modal>
@endsection
