@props(['team' => []])

<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl font-bold mb-4 text-gray-900">Il Nostro Team</h2>
            <p class="text-lg text-gray-600">Esperti qualificati al tuo servizio per garantire la massima sicurezza.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($team as $member)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-transform duration-300 hover:-translate-y-2">
                    <div class="aspect-w-3 aspect-h-4 relative">
                        <img src="{{ $member['image'] ?? 'https://ui-avatars.com/api/?name='.urlencode($member['name']).'&background=random' }}" 
                             alt="{{ $member['name'] }}" 
                             class="w-full h-80 object-cover object-top"
                             loading="lazy">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $member['name'] }}</h3>
                        <p class="text-[#2D8659] font-medium mb-3">{{ $member['role'] }}</p>
                        <p class="text-gray-600 text-sm mb-4">{{ $member['bio'] }}</p>
                        
                        @if(!empty($member['linkedin']))
                            <a href="{{ $member['linkedin'] }}" target="_blank" aria-label="LinkedIn di {{ $member['name'] }}" class="inline-flex items-center text-gray-400 hover:text-[#0077b5] transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
