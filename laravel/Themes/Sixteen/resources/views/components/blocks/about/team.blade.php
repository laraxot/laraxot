@props([
    'title' => 'Il Nostro Team',
    'subtitle' => '',
    'members' => [],
])

<section id="team" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        {{-- Section Header --}}
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
            @if($subtitle)
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">{{ $subtitle }}</p>
            @endif
        </div>

        {{-- Team Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($members as $member)
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 group">
                    {{-- Member Image --}}
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ $member['image'] ?? '/themes/Two/Main_files/images/testimonial-1.jpg' }}"
                             alt="{{ $member['name'] ?? 'Team Member' }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4 text-white">
                            <h3 class="text-xl font-bold">{{ $member['name'] ?? 'Nome' }}</h3>
                            <p class="text-brand-orange font-medium">{{ $member['role'] ?? 'Ruolo' }}</p>
                        </div>
                    </div>

                    {{-- Member Info --}}
                    <div class="p-6">
                        <p class="text-gray-600 text-sm leading-relaxed mb-4">
                            {{ $member['description'] ?? '' }}
                        </p>

                        {{-- Certifications --}}
                        @if(!empty($member['certifications']))
                            <div class="flex flex-wrap gap-2">
                                @foreach($member['certifications'] as $cert)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-brand-blue/10 text-brand-blue">
                                        <x-filament::icon icon="heroicon-o-check-badge" class="w-3 h-3 mr-1" />
                                        {{ $cert }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
