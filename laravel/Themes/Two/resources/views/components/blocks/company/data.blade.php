@props(['data' => []])

<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            
            {{-- Data List --}}
            <div class="space-y-6">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Dati Aziendali</h2>
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    @foreach($data as $item)
                        <div class="flex items-start border-b border-gray-100 last:border-0 pb-4 last:pb-0">
                            <div class="w-1/3 font-semibold text-gray-700">{{ $item['label'] }}</div>
                            <div class="w-2/3 text-gray-600">{{ $item['value'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Optional Map or Image (OpenStreetMap) --}}
            <div class="h-full min-h-[400px] bg-gray-200 rounded-xl overflow-hidden relative">
                @php $address = 'Via Vanzo 86/A, 31021 Mogliano Veneto TV'; @endphp
                <iframe 
                    width="100%" 
                    height="100%" 
                    src="https://nominatim.openstreetmap.org/search?format=iframe&q={{ urlencode($address) }}" 
                    frameborder="0" 
                    scrolling="no" 
                    marginheight="0" 
                    marginwidth="0"
                    class="absolute inset-0 w-full h-full"
                ></iframe>
            </div>

        </div>
    </div>
</section>
