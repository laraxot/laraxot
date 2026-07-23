@php
declare(strict_types=1);

@props([
    'title' => 'Argomenti Popolari',
    'tags' => [
        ['name' => 'Radioprotezione', 'count' => 45, 'slug' => 'radioprotezione'],
        ['name' => 'Normativa', 'count' => 38, 'slug' => 'normativa'],
        ['name' => 'Sicurezza', 'count' => 32, 'slug' => 'sicurezza'],
        ['name' => 'Elettromedicali', 'count' => 28, 'slug' => 'elettromedicali'],
        ['name' => 'Controllo Qualità', 'count' => 25, 'slug' => 'controllo-qualita'],
        ['name' => 'Formazione', 'count' => 22, 'slug' => 'formazione'],
        ['name' => 'Veterinaria', 'count' => 20, 'slug' => 'veterinaria'],
        ['name' => 'DPR 187/2001', 'count' => 18, 'slug' => 'dpr-187-2001'],
        ['name' => 'Dosimetria', 'count' => 15, 'slug' => 'dosimetria'],
        ['name' => 'Radiologia', 'count' => 14, 'slug' => 'radiologia'],
        ['name' => 'TC', 'count' => 12, 'slug' => 'tc'],
        ['name' => 'RM', 'count' => 11, 'slug' => 'rm'],
        ['name' => 'Mammografia', 'count' => 9, 'slug' => 'mammografia'],
        ['name' => 'Dentale', 'count' => 8, 'slug' => 'dentale'],
        ['name' => 'Accreditamento', 'count' => 7, 'slug' => 'accreditamento'],
        ['name' => 'Certificazione', 'count' => 6, 'slug' => 'certificazione'],
        ['name' => 'Audit', 'count' => 5, 'slug' => 'audit'],
        ['name' => 'Risk Management', 'count' => 4, 'slug' => 'risk-management'],
        ['name' => 'Innovazione', 'count' => 3, 'slug' => 'innovazione']
    ]
])
?>

{{-- Tags Cloud --}}
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">
                {{ $title }}
            </h3>
            <p class="text-gray-600">
                Esplora gli argomenti più discussi nella community
            </p>
        </div>
        
        <div class="flex flex-wrap gap-3 justify-center">
            @foreach($tags as $tag)
                <a href="/it/blog?tag={{ $tag['slug'] }}" 
                   class="group inline-flex items-center px-4 py-2 rounded-lg font-medium transition-all duration-300 transform hover:scale-105 hover:-translate-y-1
                          bg-white border border-gray-200 text-gray-700 hover:border-blue-500 hover:text-blue-600 hover:shadow-md">
                    <span class="relative">
                        {{ $tag['name'] }}
                        <span class="absolute -top-1 -right-2 flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                        </span>
                    </span>
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600 group-hover:bg-blue-100 group-hover:text-blue-700">
                        {{ $tag['count'] }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<style>
    @keyframes ping {
        75%, 100% {
            transform: scale(2);
            opacity: 0;
        }
    }
    
    .animate-ping {
        animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
    }
</style>