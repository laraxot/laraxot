@props(['values' => []])

<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl font-bold mb-4 text-gray-900">I Nostri Valori</h2>
            <p class="text-lg text-gray-600">I principi cardine che guidano ogni nostra azione e consulenza.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($values as $value)
                <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition-shadow duration-300">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-6">
                        @if(isset($value['icon']))
                            <x-dynamic-component :component="'heroicon-o-' . str_replace('heroicon-o-', '', $value['icon'])" class="w-8 h-8" />
                        @endif
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $value['title'] }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $value['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
